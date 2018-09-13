<?php


namespace Xpressengine\Plugins\XeroStore;


use Xpressengine\Plugins\XeroStore\Models\Order;

class OrderHandler
{
    public function register(Array $goods)
    {

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
        return $order;
    }
}