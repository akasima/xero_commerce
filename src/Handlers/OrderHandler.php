<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Carrier;
use Xpressengine\Plugins\XeroCommerce\Models\OrderAfterservice;
use Xpressengine\Plugins\XeroCommerce\Models\OrderShipment;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Models\Payment;
use Xpressengine\Plugins\XeroCommerce\Models\UserAddress;

class OrderHandler extends OrderableItemHandler
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
        $order->items->each(function(OrderItem $orderItem) use ($order) {
            if(method_exists($orderItem->product,'trashed')) {
                if($orderItem->product->trashed()){
                    $order->delete();
                    abort('500','현재 제공하지 않는 상품입니다. <br> 상품정보 : '.$orderItem->product->name);
                }
            }
        });

        return $order;
    }

    public function register($cartItems)
    {
        $order = $this->makeOrder();

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            // OrderItem을 Product에서 생성 (Product 타입별 처리가 다르기 때문 - 번들상품)
            $orderItem = $product->newOrderItem($order, $cartItem);

            $orderItem->save();

            // 커스텀옵션 연동
            $orderItem->customOptions()->createMany($cartItem->custom_options->all());

            // 주문완료후 장바구니를 비우기 위한 id저장
            $cartItem->order_id = $order->id;
            $cartItem->save();
        }

        return $this->update($order);
    }

    public function update(Order $order)
    {
        $code = Order::TEMP;

        $paidCheck = $this->paidCheck($order);
        $shipmentCheck = $this->shipmentCheck($order);
        $ASCheck = $this->afterServiceCheck($order);

        if ($paidCheck) {
            $code = $paidCheck;
        }

        if (!is_null($shipmentCheck)) {
            $code = $shipmentCheck;
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

    public function shipmentCheck(Order $order)
    {
        $hasProcessingShipment = $order->items()->whereHas('shipment', function ($query) {
            $query->where('status', OrderShipment::PROCESSING);
        })->exists();

        $hasDoneShipment = $order->items()->whereHas('shipment', function ($query) {
            $query->where('status', OrderShipment::DONE);
        })->exists();

        if ($hasProcessingShipment) {
            return Order::DELIVER;
        }

        if ($hasDoneShipment) {
            return Order::COMPLETE;
        }
    }

    public function afterServiceCheck(Order $order)
    {
        $hasExchanging = $order->items()->where('code', OrderItem::CODE_EXCHANGING)->exists();
        $hasExchanged = $order->items()->where('code', OrderItem::CODE_EXCHANGED)->exists();
        $hasRefunding = $order->items()->where('code', OrderItem::CODE_REFUNDING)->exists();
        $hasRefunded = $order->items()->where('code', OrderItem::CODE_REFUNDED)->exists();
        $hasCanceling = $order->items()->where('code', OrderItem::CODE_CANCELING)->exists();
        $hasCanceled = $order->items()->where('code', OrderItem::CODE_CANCELED)->exists();

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
        return Order::where('user_id', Auth::id() ?: 1)->latest()->first()->items;
    }

    public function getOrderItemList(Order $order, $condition = null)
    {
        if (is_null($order->id)) {
            $orderItems = $this->whereUser()
                ->when(!is_null($condition), function ($query) use ($condition) {
                    $query->where($condition);
                })
                ->with('visibleItems.shipment.carrier', 'visibleItems.productVariant')
                ->latest()->get()->pluck('visibleItems')->flatten();
        } else {
            $orderItems = $order->visibleItems()->when(!is_null($condition), function ($query) use ($condition) {
                $query->where($condition);
            })->with('shipment.carrier', 'order.visibleItems.productVariant')->latest()->get();
        }

        return $orderItems;
    }

    public function makePayment(Order $order)
    {
        $summary = $this->getSummary($order->visibleItems);

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

    public function addUserAddress($args)
    {
        $addr = $args;
        $addr['user_id'] = Auth::id();
        $addr['seq'] = UserAddress::where('user_id', Auth::id())->count() + 1;

        $item = UserAddress::updateOrCreate(
            ['nickname' => $args['nickname']],
            $addr
        );

        return $item;
    }

    public function makeShipment(Order $order, $args)
    {
        if (isset($args['shipment']['nickname'])) {
            $this->addUserAddress($args['shipment']);
        }

        $order->items->each(function (OrderItem $orderItem) use ($args) {
            if($orderItem->product->isShipped()){
                // 픽업배송일때는 배송주소를 읽어와서 입력
                $shopCarrier = $orderItem->product->getShopCarrier();
                if($shopCarrier->carrier->type == Carrier::TAKE) {
                    $shipment = new OrderShipment();
                    $shipment->order_item_id = $orderItem->id;
                    $shipment->ship_no = '';
                    $shipment->status = OrderShipment::READY;
                    $shipment->carrier_id = $shopCarrier->carrier->id;
                    $shipment->recv_name = $args['shipment']['name'];
                    $shipment->recv_phone = $args['shipment']['phone'];
                    $shipment->recv_addr = $shopCarrier->addr;
                    $shipment->recv_addr_detail = $shopCarrier->addr_detail;
                    $shipment->recv_addr_post = $shopCarrier->addr_post;
                    $shipment->save();
                } else {
                    $shipment = new OrderShipment();
                    $shipment->order_item_id = $orderItem->id;
                    $shipment->ship_no = '';
                    $shipment->status = OrderShipment::READY;
                    $shipment->carrier_id = $shopCarrier->carrier->id;
                    $shipment->recv_name = $args['shipment']['name'];
                    $shipment->recv_phone = $args['shipment']['phone'];
                    $shipment->recv_addr = $args['shipment']['addr'] ?: '';
                    $shipment->recv_addr_detail = $args['shipment']['addr_detail'];
                    $shipment->recv_addr_post = $args['shipment']['addr_post'];
                    $shipment->recv_msg = $args['shipment']['msg'];
                    $shipment->save();
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
            ->when(is_array($condition), function ($query) use ($condition) {
                $query->where($condition);
            })
            // 클로저를 사용할 수 있도록 구현
            ->when(!is_null($condition) && $condition instanceof \Closure, $condition)
            ->with('payment', 'userInfo')
            ->latest()
            ->paginate($count, ['*'], '', $page);
    }

    public function shipNoRegister(OrderItem $orderItem, $ship_no)
    {
        $shipment = $orderItem->shipment;
        $order = $orderItem->order;

        $shipment->setShipNo($ship_no);

        return $this->update($order);
    }

    public function completeShipment(OrderItem $orderItem)
    {
        $shipment = $orderItem->shipment;
        $order = $orderItem->order;
        $shipment->complete();

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
        $oa->carrier_id = $request->shipment;
        $oa->ship_no = $request->ship_no;
        $oa->received = false;
        $oa->complete = false;

        $oa->save();
    }

    public function orderCancel(Order $order, $reason)
    {
        $orderItems = $order->items;

        $orderItems->each(function ($orderItem) use ($reason) {
            $oa = new OrderAfterservice();

            $oa->order_item_id = $orderItem->id;
            $oa->type = "취소";
            $oa->reason = $reason;
            $oa->carrier_id = 0;
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
            return $orderItem->toArray();
        });
    }

    /**
     * @param $orderId
     * @return Order
     */
    public function getOrder($orderId)
    {
        $order = Order::find($orderId);

        if(is_null($order)) {
            abort(500, '해당 주문이 존재하지 않습니다.');
        }

        return $order;
    }
}
