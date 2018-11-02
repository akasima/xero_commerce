<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderAfterservice;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\XePlugin\XeroPay\PaymentService;

class OrderService
{
    /**
     * @var OrderHandler
     */
    protected $orderHandler;

    public function __construct()
    {
        $this->orderHandler = app('xero_commerce.orderHandler');
    }

    public function order(Request $request)
    {
        return $this->orderHandler->register($this->getCartsFromRequest($request));
    }

    private function getCartsFromRequest(Request $request)
    {
        $cartService = new CartService();
        return $cartService->getCartsById($request->get('cart_id'));
    }

    public function summary($order = null)
    {
        if (!is_null($order)) return $this->orderHandler->getSummary($order->orderItems);
        return $this->orderHandler->getSummary($order = null);
    }

    public function pay(Order $order, Request $request)
    {
        return $this->orderHandler->makePayment($order);
    }

    public function complete(Order $order, Request $request)
    {
        $this->orderHandler->idUpdate($order);
        return $this->orderHandler->makeDelivery($order, $request);
    }

    public function orderItemList(Order $order)
    {
        return $this->orderHandler->getOrderItemList($order);
    }

    public function orderList($page, $count, $query)
    {
        $paginate = $this->orderHandler->getOrderList($page, $count, $this->makeQueryFromArray($query));
        return [
            'data' => $paginate->map(function (Order $order) {

                return $this->orderDetail($order);
            }),
            'paginate' => $paginate
        ];

        return $this->orderHandler->getOrderList($page, $count, $this->makeQueryFromArray($query))->map(function (Order $order) {

            return $this->orderDetail($order);
        });
    }

    public function makeQueryFromArray($condition)
    {
        return function ($query) use ($condition) {
            if (isset($condition['date'])) {
                $query->whereDate('created_at', '>=', $condition['date'][0])
                    ->whereDate('created_at', '<=', $condition['date'][1]);
            }
            if (isset($condition['compare'])) {
                foreach ($condition['compare'] as $key => $value) {
                    $query->where($key, $value[0], $value[1]);
                }
            }
            if (isset($condition['equal'])) {
                foreach ($condition['equal'] as $key => $value) {
                    if ($key != 'code' || $value != 'all') {
                        $query->where($key, $value);
                    }
                }
            }
            if (isset($condition['inGroup'])) {
                foreach ($condition['inGroup'] as $key => $value) {
                    $query->whereIn($key, $value);
                }
            }
        };
    }

    public function orderDetail(Order $order)
    {
        $order->orderItems = $this->orderHandler->getOrderItemList($order);
        $order->status = $order->getStatus();
        $order->load('payment', 'userInfo');
        return $order;
    }

    public function dashBoard()
    {
        return $this->orderHandler->dashboard();
    }

    PUBLIC function setShipNo(Request $request)
    {
        foreach ($request->order_items as $items) {
            $this->orderHandler->shipNoRegister(OrderItem::find($items['id']), $items['no']);
        }
    }

    public function exchangeOrderItem(OrderItem $orderItem, Request $request)
    {
        $this->orderHandler->makeOrderAfterservice('교환', $orderItem, $request);
        return $this->orderHandler->changeOrderItem($orderItem, OrderItem::EXCHANGING);
    }

    public function refundOrderItem(OrderItem $orderItem, Request $request)
    {
        $this->orderHandler->makeOrderAfterservice('환불', $orderItem, $request);
        return $this->orderHandler->changeOrderItem($orderItem, OrderItem::REFUNDING);
    }

    public function getOrderableOrder($order_id)
    {
        return $this->orderHandler->getOrderableOrder($order_id);
    }

    public function cancel(Order $order, Request $request)
    {
        $paymentService = new PaymentService();
        $cancel = $paymentService->cancel($order, $request->get('reason'));
        if($cancel !== true) abort(500, $cancel);
        $this->orderHandler->orderCancel($order,$request->get('reason'));
    }
}
