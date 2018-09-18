<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\Goods;
use Xpressengine\Plugins\XeroCommerce\Models\Order;

class OrderHandler
{
    public function register($carts_id)
    {
        $order = $this->makeOrder();
        $carts = Cart::find($carts_id);
        foreach ($carts as $each) {
            $order->options()->save($each->option, [
                'product_id' => $each->product_id,
                'original_price' => $each->option->add_price + $each->option->product->original_price,
                'sell_price' => $each->option->product->sell_price,
                'count' => $each->count
            ]);
        }
        $order->code = $order::ORDERED;
        $order->save();
        return $order->load('options.product.shop');
    }

    public function cancel(Goods $goods)
    {

    }

    public function updateOrder()
    {

    }

    public function makeOrder()
    {
        $order = new Order();
        $order->code = $order::TEMP;
        $order->user_id = Auth::id() ? : 1;
        $order->save();
        return $order;
    }
}
