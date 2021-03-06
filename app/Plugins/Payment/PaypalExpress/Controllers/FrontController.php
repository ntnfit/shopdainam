<?php
#App\Plugins\Payment\PaypalExpress\Controllers\FrontController.php
namespace App\Plugins\Payment\PaypalExpress\Controllers;

use App\Plugins\Payment\PaypalExpress\AppConfig;
use SCart\Core\Front\Controllers\RootFrontController;
use App\Plugins\Payment\PaypalExpress\Lib\CreateOrder;
use App\Plugins\Payment\PaypalExpress\Lib\CaptureOrder;
use SCart\Core\Front\Controllers\ShopCartController;
use SCart\Core\Front\Models\ShopOrder;
use SCart\Core\Front\Models\ShopCostService;
use Throwable;

class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {

        if(empty(session('dataPayment'))) {
            return redirect(sc_route('cart'))->with(["error" => 'No session']);
        }
        $dataPayment = session('dataPayment'); 
       
        $dataMapping = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => sc_route('paypalexpress.return', ['orderId' => $dataPayment['reference_id']]),
                'cancel_url' => sc_route('cart'),
                'brand_name' => sc_store('title'),
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [$dataPayment]
        ];
       
        try {
            $orderPayment = CreateOrder::createOrder($dataMapping, false);
            if ($orderPayment->statusCode == 201) {
                session(['paypal_express_payment_id' => $orderPayment->result->id]);
                foreach ($orderPayment->result->links as $key => $link) {
                    if($link->rel === 'approve') {
                        $checkoutUrl = $link->href;
                        break;
                    }
                }
                return redirect($checkoutUrl);
            } else {
                return redirect(sc_route('cart'))->with(["error" => 'Error status code']);
            }

        }catch(\Throwable $e) {
            return redirect(sc_route('cart'))->with(["error" => $e->getMessage()]);
        }
    }


    /**
     * Process return
     *
     * @param   [type]  $orderId  [$orderId description]
     *
     * @return  [type]            [return description]
     */
    public function getReturn($orderId)
    {
        $customer = session('customer');
        if (!empty(session('paypal_express_payment_id'))) {
            $paymentId = session('paypal_express_payment_id');
            session()->forget('paypal_express_payment_id');

            if (empty(request('PayerID')) || empty(request('token'))) {
                return redirect(sc_route('cart'))->with(['error' => 'Link return invalid']);
            }

            $captureId = $this->capture($paymentId);

            if ($captureId) {
                ShopOrder::find($orderId)->update([
                    'transaction' => $captureId, 
                    'status' => sc_config('PaypalExpress_order_status_success'),
                    'payment_status' => sc_config('PaypalExpress_payment_status')
                    ]
                );
                //Add history
                $dataHistory = [
                    'order_id' => $orderId,
                    'content' => 'Transaction ' . $captureId,
                    'customer_id' => $customer->id ?? 0,
                    'order_status_id' => sc_config('PaypalExpress_order_status_success'),
                ];
                (new ShopOrder)->addOrderHistory($dataHistory);
                return (new ShopCartController)->completeOrder();
            } else {
                return redirect(sc_route('cart'))->with(['error' => 'Have an error paypal']);
            }
        } else {
            return redirect(sc_route('cart'))->with(['error' => 'Can\'t get payment id']);
        }

    }

    /**
     * Capture order
     *
     * @param   [type]  $paymentId  [$paymentId description]
     *
     * @return  [type]              [return description]
     */
    public function capture($paymentId) {
        $responseCapture =  CaptureOrder::captureOrder($paymentId);
        if ($responseCapture->statusCode == 201)
        {
            foreach($responseCapture->result->purchase_units as $purchase_unit)
            {
                foreach($purchase_unit->payments->captures as $capture)
                {    
                    return $capture->id;
                }
            }
        }
        return false;
    }

    /**
     * Process data order
     *
     * @return  [type]  [return description]
     */
    public function processOrder() {
       
        $dataOrder = session('dataOrder')?? [];
        $currency = $dataOrder['currency'] ?? '';
        $orderID = session('orderID') ?? 0;
        $arrCartDetail = session('arrCartDetail')?? null;
        $costservice=ShopCostService::sum('value');
        if(!in_array($currency, $this->plugin->currencyAllow)) {
            $msg = sc_language_render($this->plugin->pathPlugin.'::lang.currency_only_allow', ['list' => implode(',', $this->plugin->currencyAllow)]);
            (new ShopOrder)->updateStatus($orderID, sc_config('nganluong_order_status_faild', 6), $msg);
            return redirect(sc_route('cart'))->with(['error' => $msg]);
        }
        if ($orderID && $dataOrder && $arrCartDetail) {
            $dataTotal = [
                'item_total' => [
                    'currency_code' => $currency,
                    'value' => round((float)$dataOrder['subtotal'],2),
                ],
                'shipping' => [
                    'currency_code' => $currency,
                    'value' => (float)$dataOrder['shipping']+round($_COOKIE["cost"],2),
                ],
                'tax_total' => [
                    'currency_code' => $currency,
                    'value' => round((float)$dataOrder['tax'],2),
                ],
                'handling' => [
                    'currency_code' => $currency,
                    'value' => (float)($dataOrder['other_fee'] ?? 0)+round($costservice,2),
                ],
                'discount' => [
                    'currency_code' => $currency,
                    'value' =>round(abs((float)$dataOrder['discount']),2),
                ],
            ];
    
            foreach ($arrCartDetail as $item) {
                $dataItems[] = [
                    'name' => $item['name'],
                    'quantity' => $item['qty'],
                    'unit_amount' => [
                        'currency_code' => $currency,
                        'value' => sc_currency_value((float)$item['price']),
                    ],
                    'sku' => $item['product_id'],
                ];
            }

            $dataPayment = [];
            $dataPayment['reference_id'] = $orderID;
            $dataPayment['amount'] = [
                'currency_code' => $currency,
                'value' => round((float)$dataOrder['total'],2),
                'breakdown' => $dataTotal,
            ];
            $dataPayment['items'] = $dataItems;
           
            return redirect()->route('paypalexpress.index')->with('dataPayment', $dataPayment);
        } else {
            return redirect(sc_route('cart'))->with(['error' => 'Data not correct']);
        }
    }
}
