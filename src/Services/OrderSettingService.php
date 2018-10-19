<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;

class OrderSettingService
{
    /** @var OrderHandler $orderHandler */
    protected $orderHandler;

    public function __construct()
    {
        $this->orderHandler = app('xero_commerce.orderHandler');
        $this->orderHandler->auth = true;
    }

    public function dashBoard()
    {
        return $this->orderHandler->dashboard();
    }

    public function weekBoard()
    {
        return $this->orderHandler->dailyBoard();
    }

    public function list()
    {
        return $this->orderHandler->getOrderList(1,10);
    }

    public function deliveryOrderItemList()
    {
        return $this->orderHandler->getOrderItemList(new Order(), function($query){
            $query->where('code','!=', Order::TEMP);
        });
    }

    public function deliveryProcess(Request $request)
    {
        foreach($request->delivery as $delivery)
        {
            $this->orderHandler->shipNoRegister(OrderItem::find($delivery['id']),$delivery['no']);
        }
    }

    public function deliveryComplete(Request $request)
    {
        foreach($request->delivery as $delivery)
        {
            $this->orderHandler->completeDelivery(OrderItem::find($delivery));
        }
    }

    public function afterserviceList()
    {
        return $this->orderHandler->getAfterserviceList();
    }

    public function setOrderItemStatus(OrderItem $orderItem, $code)
    {
        return $this->orderHandler->changeOrderItem($orderItem, $code);
    }

    public function receiveOrderItem(OrderItem $orderItem)
    {
        return $this->orderHandler->receiveOrderAfterservice($orderItem);
    }

    public function endExchangeOrderItem(OrderItem $orderItem)
    {
        $this->orderHandler->endOrderAfterService($orderItem);
        return $this->orderHandler->changeOrderItem($orderItem, OrderItem::EXCHANGED);
    }

    public function endRefundOrderItem(OrderItem $orderItem)
    {
        $this->orderHandler->endOrderAfterService($orderItem);
        return $this->orderHandler->changeOrderItem($orderItem, OrderItem::REFUNDED);
    }
}
