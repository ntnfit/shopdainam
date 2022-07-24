<?php
#App\Plugins\Payment\Nganluong\Controllers\FrontController.php
namespace App\Plugins\Payment\Nganluong\Controllers;

use App\Plugins\Payment\Nganluong\AppConfig;
use SCart\Core\Front\Controllers\RootFrontController;
use Illuminate\Http\Request;
use SCart\Core\Front\Models\ShopOrder;
use SCart\Core\Front\Controllers\ShopCartController;
use App\Plugins\Payment\Nganluong\Controllers\Checkout;
class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {
        return view($this->plugin->pathPlugin.'::Front',
            [
                //
            ]
        );
    }

    public function processOrder(){
        $dataOrder = session('dataOrder')?? [];
        $currency = $dataOrder['currency'] ?? '';
        $orderID = session('orderID') ?? 0;
          //Validate currency
          if(!in_array($currency, $this->plugin->currencyAllow)) {
            $msg = sc_language_render($this->plugin->pathPlugin.'::lang.currency_only_allow', ['list' => implode(',', $this->plugin->currencyAllow)]);
            (new ShopOrder)->updateStatus($orderID, sc_config('nganluong_order_status_faild', 6), $msg);
            return redirect(sc_route('cart'))->with(['error' => $msg]);
        }
        //Validate order id exist
        if (session('orderID')) {
             return view('nganluong.payment')->with('OrderAmount',$dataOrder['total'])
                                            ->with('Ofullname',$dataOrder['sent_fullname'])
                                            ->with('Oemail',$dataOrder['sent_email'])
                                            ->with('Ophone',$dataOrder['sent_phone'])
                                            ->with('OrderId',$orderID);
        } else {
            return redirect(sc_route('cart'))
                ->with(['error' => sc_language_render('cart.order_not_found')]);
        }
    }
    public function success(Request $request)
    {
        $url='https://www.nganluong.vn/checkout.api.nganluong.post.php';
        $nlcheckout=new Checkout(sc_config('MERCHANT_ID'),sc_config('MERCHANT_PASS'),sc_config('NL_RECEIVER'),$url);
        
        $nl_result = $nlcheckout->GetTransactionDetail($_GET['token']);
        if($nl_result){
   
            $nl_errorcode           = (string)$nl_result->error_code;
            $nl_transaction_status  = (string)$nl_result->transaction_status;
            if($nl_errorcode == '00') {
                if($nl_transaction_status == '00') {
                    //trạng thái thanh toán thành công
                    $json = json_encode($nl_result);
                    $data = json_decode($json,TRUE);
                    
                    ShopOrder::find($data['order_code'])->update([
                                'transaction' => $data['transaction_id'], 
                                'status' => sc_config('nganluong_order_status_success', 2),
                                'payment_status' => sc_config('nganluong_payment_status', 3)
                                ]);
                
                            //Add history
                            $dataHistory = [
                                'order_id' =>$data['order_code'],
                                'content' => 'Transaction ' . $data['transaction_id'],
                                'customer_id' => $data['buyer_fullname'] ?? 0,
                                'order_status_id' => sc_config('nganluong_order_status_success', 2),
                            ];
                            (new ShopOrder)->addOrderHistory($dataHistory);
                            //Complete order
                
                            return (new ShopCartController)->completeOrder();
                }
                
            }else{ 
               
                $msg = sc_language_render($this->plugin->pathPlugin.'::lang.error_number', ['code' =>$nlcheckout->GetErrorMessage($nl_errorcode)]);
                (new ShopOrder)->updateStatus($data['order_code'], sc_config('nganluong_order_status_faild', 6), $msg);
                return redirect(sc_route('cart'))->with(['error' => $msg]);
                
            }
        }
    }

    public function cancel(Request $request)
    {
        (new ShopOrder)->updateStatus($request['id'], sc_config('nganluong_order_status_faild', 6));
        return redirect(sc_route('cart'));
    }
}
