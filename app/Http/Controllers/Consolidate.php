<?php

namespace App\Http\Controllers;

use SCart\Core\Front\Controllers\RootFrontController;
use Illuminate\Http\Request;
use SCart\Core\Front\Models\ShopOrder;
use SCart\Core\Front\Models\ShopOrderDetail;
class Consolidate extends Controller
{
    public function order(Request $request)
    {
       
        $orders = (new ShopOrder)
        ->where('payment_status',3)->where('status',2)->get();
      
       return response()->json($orders, 200);
    }
    public function orderDetail(Request $request, $id)
    {
        $order = (new ShopOrderDetail)->where('sc_shop_order_detail.order_id', $id)
                ->join('sc_shop_product','sc_shop_order_detail.product_id', '=', 'sc_shop_product.id')
                ->get();
        if ($order) {
            $dataReturn = $order;
        } else {
            $dataReturn = [
                'error' => 1,
                'msg' => 'Not found',
                'detail' => 'Order not found or no permission!',
            ];
        }
        return response()->json($dataReturn, 200);
    }
    
    
}
