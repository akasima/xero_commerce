<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroStore\Goods;
use Xpressengine\Plugins\XeroStore\Models\Cart;
use Xpressengine\Plugins\XeroStore\Models\Order;
use Xpressengine\User\Models\User;

class OrderHandler
{
    public function register($carts_id)
    {
        $order = $this->makeOrder();
        $carts = Cart::find($carts_id);
        foreach ($carts as $each) {
            $order->options()->save($each->option, [
                'product_id' => $each->product_id,
                'price' => $each->price,
                'count' => $each->count
            ]);
        }
        $order->code = $order::ORDERED;
        return $order->save();
    }

    public function cancel(Goods $goods)
    {

    }

    public function exchange(Goods $goods)
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
        return $order;
    }
}
