<?php
#App\Plugins\Payment\AuthorizeNet\Controllers\FrontController.php
namespace App\Plugins\Payment\AuthorizeNet\Controllers;

use App\Plugins\Payment\AuthorizeNet\AppConfig;
use SCart\Core\Front\Controllers\RootFrontController;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Http\Request;
use SCart\Core\Front\Models\ShopOrder;
use SCart\Core\Front\Controllers\ShopCartController;
class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {
       
    }

    public function processOrder(){
        $dataOrder = session('dataOrder')?? [];
        $currency = $dataOrder['currency'] ?? '';
        $orderID = session('orderID') ?? 0;

        //Validate currency
        if(!in_array($currency, $this->plugin->currencyAllow)) {
            $msg = sc_language_render($this->plugin->pathPlugin.'::lang.currency_only_allow', ['list' => implode(',', $this->plugin->currencyAllow)]);
            (new ShopOrder)->updateStatus($orderID, sc_config('authorize_order_status_faild', 6), $msg);
            return redirect(sc_route('cart'))->with(['error' => $msg]);
        }
        //Validate order id exist
        if (session('orderID')) {
             return view('Payment')->with('dataOrder',$dataOrder['total']);
        } else {
            return redirect(sc_route('cart'))
                ->with(['error' => sc_language_render('cart.order_not_found')]);
        }
    }

    public function prepareDataBeforeSend()
    {  
    }
    public function payment(Request $request)
    {
      
        $orderID = session('orderID') ?? 0;
        $dataOrder = session('dataOrder')?? [];
        $ANET_ENV=sc_config('authorize_env');
        $amount=$dataOrder['total'] ?? '';
        $refID = $orderID.time(); 

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(sc_config('ANET_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(sc_config('ANET_TRANSACTION_KEY'));

        $creditCard = new AnetAPI\CreditCardType(); 
        $cardNumber = preg_replace('/\s+/', '',$request->card_number);
        $creditCard->setCardNumber( $cardNumber); 
        $creditCard->setExpirationDate($request->card_exp_year_month); 
        $creditCard->setCardCode($request->card_cvc); 

        // Add the payment data to a paymentType object 
        $paymentOne = new AnetAPI\PaymentType(); 
        $paymentOne->setCreditCard($creditCard); 
        
        // Create order information 
        $order = new AnetAPI\OrderType(); 
        $order->setDescription($orderID); 
        
        // Set the customer's identifying information 
        $customerData = new AnetAPI\CustomerDataType(); 
        $customerData->setType("individual"); 
        $customerData->setId(mt_rand(10000, 99999)); 
      //  $customerData->setEmail($request->email); 
     
     // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($request->firstname);
        $customerAddress->setLastName($request->last_name);
        //$customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress($request->address);
        $customerAddress->setCity($request->city);
        $customerAddress->setState($request->state);
        $customerAddress->setZip($request->zip);
        $customerAddress->setCountry($request->country);   
        // Create a transaction 
        $transactionRequestType = new AnetAPI\TransactionRequestType(); 
        $transactionRequestType->setTransactionType("authCaptureTransaction");    
        $transactionRequestType->setAmount(round($amount,2)); 
        $transactionRequestType->setOrder($order); 
        $transactionRequestType->setPayment($paymentOne); 
        $transactionRequestType->setCustomer($customerData); 
        $transactionRequestType->setBillTo($customerAddress);
        $request = new AnetAPI\CreateTransactionRequest(); 
        $request->setMerchantAuthentication($merchantAuthentication); 
        $request->setRefId($refID); 
        $request->setTransactionRequest($transactionRequestType); 
        $controller = new AnetController\CreateTransactionController($request); 
        $response = $controller->executeWithApiResponse(constant("\\net\authorize\api\constants\ANetEnvironment::$ANET_ENV")); 
        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    // echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                    // echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                    // echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                    // echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                    // echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
                    ShopOrder::find($orderID)->update([
                        'transaction' => $tresponse->getTransId(), 
                        'status' => sc_config('authorize_order_status_success', 2),
                        'payment_status' => sc_config('authorize_payment_status', 3)
                        ]);
        
                    //Add history
                    $dataHistory = [
                        'order_id' => $orderID,
                        'content' => 'Transaction ' . $tresponse->getTransId(),
                        'customer_id' => $customer->id ?? 0,
                        'order_status_id' => sc_config('authorize_order_status_success', 2),
                    ];
                    (new ShopOrder)->addOrderHistory($dataHistory);
                    //Complete order
        
                    return (new ShopCartController)->completeOrder();
                } else {
                    echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                        $msg = sc_language_render($this->plugin->pathPlugin.'::lang.error_number', ['code' => $tresponse->getErrors()[0]->getErrorCode(). $tresponse->getErrors()[0]->getErrorText()]);
                        (new ShopOrder)->updateStatus($orderID, sc_config('authorize_order_status_faild', 6), $msg);
                        return redirect(sc_route('cart'))->with(['error' => $msg]);
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                         $msg = sc_language_render($this->plugin->pathPlugin.'::lang.error_number', ['code' =>$tresponse->getErrors()[0]->getErrorCode()."".$tresponse->getErrors()[0]->getErrorText()]);
                         (new ShopOrder)->updateStatus($orderID, sc_config('authorize_order_status_faild', 6), $msg);
                        return redirect(sc_route('cart'))->with(['error' => $msg]);
                } else {
                        $msg = sc_language_render($this->plugin->pathPlugin.'::lang.error_number', ['code' =>$response->getMessages()->getMessage()[0]->getText()]);
                        (new ShopOrder)->updateStatus($orderID, sc_config('authorize_order_status_faild', 6), $msg);
                        return redirect(sc_route('cart'))->with(['error' => $msg]);
                }
            }
        } else {
            $msg = sc_language_render($this->plugin->pathPlugin.'::lang.error_number', ['code' =>"No response returned"]);
            (new ShopOrder)->updateStatus($orderID, sc_config('authorize_order_status_faild', 6), $msg);
            return redirect(sc_route('cart'))->with(['error' => $msg]);
        }
         
        return $response;
    
    }
}
