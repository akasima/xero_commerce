<?php

namespace Xpressengine\Plugins\XeroCommerce\Events;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItemGroup;
use Xpressengine\Plugins\XeroCommerce\Models\OrderLog;
use Xpressengine\Plugins\XeroCommerce\Notifications\OrderCancel;
use Xpressengine\Plugins\XeroCommerce\Notifications\OrderMake;

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
                case Order::ORDERED:
                case Order::PAID:
                    $original = $order->getOriginal('code');
                    if ($original === Order::TEMP) {
                        Notification::route('mail', Auth::user()->email)
                            ->notify(new OrderMake($order));
                    }
                    break;
                case Order::CANCELED:
                    $original = $order->getOriginal('code');
                    if ($original !== Order::CANCELED) {
                        Notification::route('mail', Auth::user()->email)
                            ->notify(new OrderCancel($order));
                    }
                    break;
            }
        }
    }

    public function saved(Order $order)
    {
        $log = new OrderLog();
        $log->order_id = $order->id;
        $log->status=$order->getStatus();
        $log->ip = request()->ip();
        $log->url = request()->url();
        $log->save();
    }

}
