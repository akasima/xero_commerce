<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\OrderDelivery;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;

class OrderHandler extends OrderUnitHandler
{
    public function register($carts_id)
    {
        $order = $this->makeOrder();
        $carts = Cart::find($carts_id);
        foreach ($carts as $cart) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $cart->orderable->goods()->save($orderItem);
            $orderItem->original_price = $cart->getOriginalPrice();
            $orderItem->sell_price = $cart->getSellPrice();
            $orderItem->count = $cart->getCount();
            $orderItem->save();
        }
        return $this->update($order);
    }

    public function update(Order $order)
    {
        $code = Order::TEMP;

        $paidCheck = $this->paidCheck($order);
        $deliveryCheck = $this->deliveryCheck($order);
        $ASCheck = $this->afterServiceCheck($order);

        if ($paidCheck) {
            $code = $paidCheck;
        }
        if (!is_null($deliveryCheck)) {
            $code = $deliveryCheck;
        }
        if (!is_null($ASCheck)) {
            $code = $ASCheck;
        }
        $order->code = $code;
        $order->save();
        return $order;
    }

    public function paidCheck(Order $order)
    {
        $payment = $order->payment;
        if ($payment) {
            if ($payment->is_paid) {
                return Order::PAID;
            } else {
                return Order::ORDERED;
            }
        } else {
            return Order::TEMP;
        }
    }

    public function deliveryCheck(Order $order)
    {
        $hasReadyDelivery = $order->orderItems()->whereHas('delivery', function ($query) {
            $query->where('status', OrderDelivery::READY);
        })->exists();
        $hasProcessingDelivery = $order->orderItems()->whereHas('delivery', function ($query) {
            $query->where('status', OrderDelivery::PROCESSING);
        })->exists();
        $hasDoneDelivery = $order->orderItems()->whereHas('delivery', function ($query) {
            $query->where('status', OrderDelivery::READY);
        })->exists();
        if ($hasReadyDelivery || $hasProcessingDelivery) {
            return Order::DELIVER;
        }
        if ($hasDoneDelivery) {
            return Order::COMPLETE;
        }
    }

    public function afterServiceCheck(Order $order)
    {
        $hasExchanging = $order->orderItems()->where('code', OrderItem::EXCHANGING)->exists();
        $hasExchanged = $order->orderItems()->where('code', OrderItem::EXCHANGED)->exists();
        $hasRefunding = $order->orderItems()->where('code', OrderItem::REFUNDING)->exists();
        $hasRefunded = $order->orderItems()->where('code', OrderItem::REFUNDED)->exists();

        if ($hasExchanging) {
            return Order::EXCHANGING;
        } elseif ($hasRefunding) {
            return Order::REFUNDING;
        } elseif ($hasRefunded) {
            return Order::REFUNDED;
        } elseif ($hasExchanged) {
            return Order::EXCHANGED;
        }
    }

    public function updateOrder()
    {

    }

    public function makeOrder()
    {
        $order = new Order();
        $order->code = $order::TEMP;
        $order->user_id = Auth::id() ?: 1;
        $order->save();
        return $order;
    }

    public function getGoodsList()
    {
        return Order::where('user_id', Auth::id() ?: 1)->latest()->first()->orderItems;
    }
}
