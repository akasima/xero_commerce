<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;
use Xpressengine\Plugins\XeroCommerce\Services\OrderSettingService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct()
    {
        $this->orderService = new OrderSettingService();
    }

    public function index()
    {
        return \XePresenter::make('xero_commerce::views.index', ['title' => 'xero_commerce']);
    }

    public function dash()
    {
        XeFrontend::js('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js')->load();
        return \XePresenter::make('xero_commerce::views.setting.order.dash', [
            'title' => 'xero_commerce',
            'dash' => $this->orderService->dashBoard()
        ]);
    }

    public function list()
    {

    }

    public function payment()
    {

    }

    public function delivery()
    {
        return \XePresenter::make('xero_commerce::views.setting.order.delivery', [
            'title' => 'xero_commerce',
            'orderItems'=> $this->orderService->deliveryOrderItemList()
        ]);
    }

    public function registerDelivery()
    {

    }

    public function processDelivery(Request $request)
    {
        $this->orderService->deliveryProcess($request);
    }

    public function completeDelivery(Request $request)
    {
        $this->orderService->deliveryComplete($request);
    }

    public function buyOption()
    {

    }

    public function buyList()
    {

    }

    public function afterservice()
    {
        return \XePresenter::make('xero_commerce::views.setting.order.as', [
            'list'=>$this->orderService->afterserviceList()
        ]);
    }

    public function afterserviceReceive(OrderItem $orderItem)
    {
        return $this->orderService->receiveOrderItem($orderItem);
    }

    public function afterserviceEnd($type, OrderItem $orderItem)
    {

        if($type =='교환')$this->orderService->endExchangeOrderItem($orderItem);
        if($type =='환불')$this->orderService->endRefundOrderItem($orderItem);
        if( get_class($orderItem->sellType) =='Xpressengine\Plugins\XeroCommerce\Models\Product' )
        {
            return route('xero_commerce::setting.product.show', [
                'productId' => $orderItem->sellType->id
            ]);
        } else {
            return route('xero_commerce::setting.product.index');
        }
    }
}