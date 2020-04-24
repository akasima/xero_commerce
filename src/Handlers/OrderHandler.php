<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\CartGroup;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Models\OrderAfterservice;
use Xpressengine\Plugins\XeroCommerce\Models\OrderDelivery;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItemGroup;
use Xpressengine\Plugins\XeroCommerce\Models\Payment;
use Xpressengine\Plugins\XeroCommerce\Models\SellGroup;
use Xpressengine\Plugins\XeroCommerce\Models\UserDelivery;

class OrderHandler extends SellSetHandler
{
    public $auth = false;

    public function whereUser()
    {
        return ($this->auth) ? new Order() : Order::where('user_id', Auth::id());
    }

    public function getOrderableOrder($id)
    {
        if (!$order = $this->whereUser()->where('code', Order::TEMP)->find($id)) {
            abort(500, '잘못된 주문 요청입니다.');
        }
        $order->orderItems->each(function(OrderItem $orderItem)use($order){
            if(method_exists($orderItem->forcedSellType(),'trashed')){
                if($orderItem->forcedSellType()->trashed()){
                    $order->delete();
                    abort('500','현재 제공하지 않는 상품입니다. <br> 상품정보 : '.$orderItem->forcedSellType()->getName());
                }
            }
            $orderItem->sellGroups->each(function(SellGroup $sellGroup)use($order){
                if(method_exists($sellGroup->sellSet->forcedSellType(),'trashed')) {
                    if ($sellGroup->forcedSellUnit()->trashed()) {
                        $order->delete();
                        abort('500', '현재 제공하지 않는 상품옵션입니다. <br> 상품정보 : ' . $sellGroup->forcedSellUnit()->getName());
                    }
                }
            });
        });

        return $order;
    }

    public function register($carts)
    {
        $order = $this->makeOrder();

        foreach ($carts as $cart) {
            $orderItem = new OrderItem();

            $orderItem->order_id = $order->id;
            $orderItem->delivery_pay = $cart->delivery_pay;
            $orderItem->sellType()->associate($cart->forcedSellType());
            $orderItem->original_price = $orderItem->getOriginalPrice();
            $orderItem->sell_price = $orderItem->getSellPrice();
            $orderItem->code = 0;

            $orderItem->save();

            $cart->sellGroups->each(function (CartGroup $cartGroup) use (&$orderItem) {
                $orderItemGroup = new OrderItemGroup();

                $orderItemGroup->sellUnit()->associate($cartGroup->forcedSellUnit());
                $orderItemGroup->setCount($cartGroup->getCount());
                $orderItemGroup->setCustomValues($cartGroup->getCustomValues());

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
        $payment = $order->payment()->first();

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
        $hasCanceling = $order->orderItems()->where('code', OrderItem::CANCELING)->exists();
        $hasCanceled = $order->orderItems()->where('code', OrderItem::CANCELED)->exists();

        if ($hasExchanging) {
            return Order::EXCHANGING;
        } elseif ($hasRefunding) {
            return Order::REFUNDING;
        } elseif ($hasCanceling) {
            return Order::CANCELING;
        } elseif ($hasRefunded) {
            return Order::REFUNDED;
        } elseif ($hasExchanged) {
            return Order::EXCHANGED;
        } elseif ($hasCanceled) {
            return Order::CANCELED;
        }
    }

    public function makeOrder()
    {
        $now = now();
        $order = new Order();

        $order->code = $order::TEMP;
        $order->order_no = $now->format('YmdHis') . '-' .
            sprintf("%'.06d", $order->whereDate('created_at', $now->toDateString())->count());
        $order->user_id = Auth::id() ?: request()->ip();

        $order->save();

        return $order;
    }

    public function getSellSetList()
    {
        return Order::where('user_id', Auth::id() ?: 1)->latest()->first()->orderItems;
    }

    public function getOrderItemList(Order $order, $condition = null)
    {
        if (is_null($order->id)) {
            $orderItems = $this->whereUser()
                ->when(!is_null($condition), function ($query) use ($condition) {
                    $query->where($condition);
                })
                ->with('orderItems.delivery.company', 'orderItems.order.orderItems.sellGroups.sellSet')
                ->latest()->get()->pluck('orderItems')->flatten();
        } else {
            $orderItems = $order->orderItems()->when(!is_null($condition), function ($query) use ($condition) {
                $query->where($condition);
            })->with('delivery.company', 'order.orderItems.sellGroups.sellSet')->latest()->get();
        }

        return $orderItems->map(function (OrderItem $orderItem) {
            return $orderItem->getJsonFormat();
        });
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
        $payment->is_paid=false;

        if ($pay = $order->xeropay) {
            $payment->method = $pay->method;
            $payment->info = $pay->info;
            $payment->is_paid = $pay->is_paid_method;
            $payment->receipt = $pay->receipt;
        }

        $payment->save();

        return $this->update($order);
    }

    public function idUpdate(Order $order)
    {
        $order->user_id = Auth::id();
        $this->update($order);
    }

    public function addUserDelivery($args)
    {
        $del = $args;
        $del['user_id'] = Auth::id();
        $del['seq'] = UserDelivery::where('user_id', Auth::id())->count() + 1;

        $item = UserDelivery::updateOrCreate(
            ['nickname' => $args['nickname']],
            $del
        );

        return $item;
    }

    public function makeDelivery(Order $order, $args)
    {
        if (isset($args['delivery']['nickname'])) {
            $this->addUserDelivery($args['delivery']);
        }

        $order->orderItems->each(function (OrderItem $orderItem) use ($args) {
            if($orderItem->sellType->isDelivered()){
                // 픽업배송일때는 배송주소를 읽어와서 입력
                $shopDelivery = $orderItem->sellType->getDelivery();
                if($shopDelivery->company->type == DeliveryCompany::TAKE) {
                    $delivery = new OrderDelivery();
                    $delivery->order_item_id = $orderItem->id;
                    $delivery->ship_no = '';
                    $delivery->status = OrderDelivery::READY;
                    $delivery->company_id = $shopDelivery->company->id;
                    $delivery->recv_name = $args['delivery']['name'];
                    $delivery->recv_phone = $args['delivery']['phone'];
                    $delivery->recv_addr = $shopDelivery->addr;
                    $delivery->recv_addr_detail = $shopDelivery->addr_detail;
                    $delivery->recv_addr_post = $shopDelivery->addr_post;
                    $delivery->save();
                } else {
                    $delivery = new OrderDelivery();
                    $delivery->order_item_id = $orderItem->id;
                    $delivery->ship_no = '';
                    $delivery->status = OrderDelivery::READY;
                    $delivery->company_id = $shopDelivery->company->id;
                    $delivery->recv_name = $args['delivery']['name'];
                    $delivery->recv_phone = $args['delivery']['phone'];
                    $delivery->recv_addr = $args['delivery']['addr'] ?: '';
                    $delivery->recv_addr_detail = $args['delivery']['addr_detail'];
                    $delivery->recv_addr_post = $args['delivery']['addr_post'];
                    $delivery->recv_msg = $args['delivery']['msg'];
                    $delivery->save();
                }
            }
        });

        return $this->update($order);
    }

    public function dashboard()
    {
        $count = collect(Order::STATUS)->map(function ($name, $code) {
            return $this->whereUser()->where('code', $code)->count();
        });

        return collect(Order::STATUS)->combine($count);

        return Order::where('user_id', $user_id)->where('code', '!=', Order::TEMP)->get()->groupBy(function (Order $order) {
            return $order->getStatus();
        })->map(function ($codes) {
            return $codes->count();
        });
    }

    public function dailyBoard()
    {
        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $days[] = now()->subDays($i)->toDateString();
        }

        $counts = collect($days)->map(function ($item) {
            return $this->whereUser()->where('code','!=',Order::TEMP)->whereDate('created_at', $item)->count();
        })->all();

        return [
            'days' => $days,
            'count' => $counts
        ];
    }

    public function getOrderList($page, $count, $condition = null)
    {
        return $this->whereUser()
            ->where('code', '!=', Order::TEMP)
            ->when(!is_null($condition), function ($query) use ($condition) {
                $query->where($condition);
            })
            ->with('orderItems.delivery', 'payment', 'userInfo')
            ->latest()
            ->paginate($count, ['*'], '', $page);
    }

    public function shipNoRegister(OrderItem $orderItem, $ship_no)
    {
        $delivery = $orderItem->delivery;
        $order = $orderItem->order;

        $delivery->setShipNo($ship_no);

        return $this->update($order);
    }

    public function completeDelivery(OrderItem $orderItem)
    {
        $delivery = $orderItem->delivery;
        $order = $orderItem->order;
        $delivery->complete();

        $this->update($order);
    }

    public function changeOrderItem(OrderItem $orderItem, $code)
    {
        $order = $orderItem->order;
        $orderItem->code = $code;
        $orderItem->save();

        return $this->update($order);
    }

    public function makeOrderAfterservice($type, OrderItem $orderItem, Request $request)
    {
        $oa = new OrderAfterservice();

        $oa->order_item_id = $orderItem->id;
        $oa->type = $type;
        $oa->reason = $request->reason;
        $oa->delivery_company_id = $request->delivery;
        $oa->ship_no = $request->ship_no;
        $oa->received = false;
        $oa->complete = false;

        $oa->save();
    }

    public function orderCancel(Order $order, $reason)
    {
        $orderItems = $order->orderItems;

        $orderItems->each(function ($orderItem) use ($reason) {
            $oa = new OrderAfterservice();

            $oa->order_item_id = $orderItem->id;
            $oa->type = "취소";
            $oa->reason = $reason;
            $oa->delivery_company_id = 0;
            $oa->ship_no = "";
            $oa->received = false;
            $oa->complete = false;

            $oa->save();

            $this->changeOrderItem($orderItem, Order::CANCELED);
        });
        return $this->update($order);
    }

    public function receiveOrderAfterservice(OrderItem $orderItem)
    {
        $oa = $orderItem->afterService;
        $oa->received = true;

        $oa->save();
    }

    public function endOrderAfterService(OrderItem $orderItem)
    {
        $oa = $orderItem->afterService;
        $oa->complete = true;

        $oa->save();
    }

    public function getAfterserviceList()
    {
        $orders = $this->whereUser()->pluck('id');

        $orderItems = OrderItem::whereIn('order_id', $orders)->has('afterService')->with('afterService')->get();

        return $orderItems->map(function (OrderItem $orderItem) {
            return $orderItem->getJsonFormat();
        });
    }
}
