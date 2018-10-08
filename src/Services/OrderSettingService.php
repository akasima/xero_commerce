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
}
