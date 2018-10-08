<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\CartGroup;
use Xpressengine\Plugins\XeroCommerce\Models\OrderDelivery;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItemGroup;
use Xpressengine\Plugins\XeroCommerce\Models\Payment;
use Xpressengine\Plugins\XeroCommerce\Models\UserDelivery;
use Xpressengine\User\Models\User;

class OrderHandler extends SellSetHandler
{
    public function register($carts)
    {
        $order = $this->makeOrder();
        foreach ($carts as $cart) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $cart->sellType->orderItems()->save($orderItem);
            $orderItem->save();
            $cart->sellGroups->each(function (CartGroup $cartGroup) use (&$orderItem) {
                $orderItemGroup = new OrderItemGroup();
                $cartGroup->sellUnit->orderItemGroup()->save($orderItemGroup);
                $orderItemGroup->setCount($cartGroup->getCount());
                $orderItem->sellGroups()->save($orderItemGroup);
            });
            $cart->order_id = $order->id;
            $cart->save();
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
        $hasProcessingDelivery = $order->orderItems()->whereHas('delivery', function ($query) {
            $query->where('status', OrderDelivery::PROCESSING);
        })->exists();
        $hasDoneDelivery = $order->orderItems()->whereHas('delivery', function ($query) {
            $query->where('status', OrderDelivery::DONE);
        })->exists();
        if ($hasProcessingDelivery) {
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
        $now = now();
        $order = new Order();
        $order->code = $order::TEMP;
        $order->order_no = $now->format('YmdHis') . '-' . sprintf("%'.06d", $order->whereDate('created_at', $now->toDateString())->count());
        $order->user_id = Auth::id() ?: 1;
        $order->save();
        return $order;
    }

    public function getSellSetList()
    {
        return Order::where('user_id', Auth::id() ?: 1)->latest()->first()->orderItems;
    }

    public function getOrderItemList(Order $order)
    {
        return $order->orderItems;
    }

    public function makePayment(Order $order)
    {
        $summary = $this->getSummary($order->orderItems);
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->method = '';
        $payment->info = '';
        $payment->price = $summary['sum'];
        $payment->discount = $summary['discount_price'];
        $payment->millage = 0;
        $payment->fare = $summary['fare'];
        $payment->save();
        return $this->update($order);
    }

    public function makeDelivery(Order $order, Request $request)
    {
        if (isset($request->delivery['nickname'])) {
            $del = $request->delivery;
            $del['user_id']=Auth::id();
            $del['seq']=UserDelivery::where('user_id',Auth::id())->count()+1;
            UserDelivery::updateOrCreate(
                ['nickname'=>$request->delivery['nickname']],
                $del
            );
        }
        $order->orderItems->each(function (OrderItem $orderItem) use ($request) {
            $delivery = new OrderDelivery();
            $delivery->order_item_id = $orderItem->id;
            $delivery->ship_no = '';
            $delivery->status=OrderDelivery::READY;
            $delivery->company_id = $orderItem->sellType->shop->getDefaultDeliveryCompany()->id;
            $delivery->recv_name = $request->delivery['name'];
            $delivery->recv_phone = $request->delivery['phone'];
            $delivery->recv_addr = $request->delivery['addr']? : '';
            $delivery->recv_addr_detail = $request->delivery['addr_detail'];
            $delivery->recv_msg = $request->delivery['msg'];
            $delivery->save();
        });
        return $this->update($order);
    }

    public function dashboard()
    {
        $user_id = Auth::id() ?: User::first()->getId();
        $count = collect(Order::STATUS)->map(function ($name, $code) use ($user_id) {
            return Order::where('user_id', $user_id)->where('code', $code)->count();
        });
        return collect(Order::STATUS)->combine($count);
        return Order::where('user_id', $user_id)->where('code', '!=', Order::TEMP)->get()->groupBy(function (Order $order) {
            return $order->getStatus();
        })->map(function ($codes) {
            return $codes->count();
        });
    }

    public function getOrderList($page, $count, $condition)
    {
        return Order::where('code','!=',Order::TEMP)
            ->where('user_id', Auth::id()? : User::first()->id)
            ->where(function ($query) use($condition){
                foreach($condition as $value) {
                    $query->where($value[0], $value[1], $value[2]);
                }
            })
            ->with('orderItems.delivery', 'payment')
            ->limit($count)
            ->offset(($page-1)*$count)
            ->get();
    }

    public function getDeliveryOrderItemList()
    {
        return OrderItem::whereHas('order',function($query){
            $query->where('code', '!=' ,Order::TEMP)
                ->where('user_id', Auth::id());
        })->with('order', 'delivery.company')->get();
    }

    public function shipNoRegister(OrderItem $orderItem, $ship_no)
    {
        $delivery = $orderItem->delivery;
        $order = $orderItem->order;

        $delivery->setShipNo($ship_no);

        $this->update($order);
    }
}
