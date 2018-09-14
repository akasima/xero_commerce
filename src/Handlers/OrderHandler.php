<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

use Xpressengine\Plugins\XeroStore\Goods;
use Xpressengine\Plugins\XeroStore\Interfaces\Order;

class OrderHandler
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function register(Array $goods)
    {
        $order = $this->makeOrder();
        foreach ($goods as $each) {
            $order->options()->save($each->getOption(), [
                'product_id' => $each->getProduct()->id,
                'price' => $each->getPrice(),
                'count' => $each->getCount()
            ]);
        }
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
        return $this->order->readyToOrder();
    }
}
