<?php

namespace Xpressengine\Plugins\XeroCommerce\Events;


use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItemGroup;

class OrderObserver
{
    public function updated(Order $order)
    {
        $dirty = $order->getDirty();
        if (isset($dirty['code'])) {
            switch ($dirty['code']) {
                case Order::DELIVER:
                    $orderGroups = $order->orderGroup;
                    $orderGroups->each(function (OrderItemGroup $group) {
                        $unit = $group->sellUnit;
                        $unit->stock = $unit->stock - $group->getCount();
                        $unit->save();
                    });
                    break;
            }
        }
    }

}
