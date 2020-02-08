<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\ExcelExport\DeliveryExport;
use Xpressengine\Plugins\XeroCommerce\ExcelExport\OrderCheckExport;
use Xpressengine\Plugins\XeroCommerce\ExcelImport\DeliveryImport;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;
use Xpressengine\Plugins\XeroCommerce\Services\OrderSettingService;

class OrderController extends SettingBaseController
{
    protected $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderSettingService();
    }

    public function index()
    {
        return \XePresenter::make('xero_commerce::views.index', ['title' => 'xero_commerce']);
    }

    public function dash()
    {
        XeFrontend::js('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js')->load();

        return \XePresenter::make('order.dash', [
            'title' => 'xero_commerce',
            'dash' => $this->orderService->dashBoard(),
            'week' => $this->orderService->weekBoard(),
            'list' => $this->orderService->list()
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
        return \XePresenter::make('order.delivery', [
            'title' => 'xero_commerce',
            'orderItems' => $this->orderService->deliveryOrderItemList()
        ]);
    }

    public function deliveryExcelExport()
    {
        $orderItemList = $this->orderService->deliveryOrderItemList();
        $orderItemList = $orderItemList->filter(function($item){return !$item['delivery']->ship_no;});
        return Excel::download(new DeliveryExport($orderItemList),now()->format('YmdHis').'.xlsx');
    }

	public function OrderCheckExcelExport()//02.06수정
    {
        $orderItemList = $this->orderService->deliveryOrderItemList();
        $orderItemList = $orderItemList->filter(function($item){return !$item['delivery']->ship_no;});
        return Excel::download(new OrderCheckExport($orderItemList),now()->format('YmdHis').'.xlsx');
    }

    public function deliveryExcelImport(Request $request)
    {
        $handler = app('xero_commerce.orderHandler');
        $dataProcess = Excel::toArray(new DeliveryImport(),$request->file('delivery'));
        foreach($dataProcess[0] as $key=>$data){
            if($key && $data[3]){
                $handler->shipNoRegister(OrderItem::find((int)$data[0]),$data[3]);
            }
        }
        return redirect()->route('xero_commerce::setting.order.delivery');
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
        return \XePresenter::make('order.as', [
            'list' => $this->orderService->afterserviceList()
        ]);
    }

    public function afterserviceReceive(OrderItem $orderItem)
    {
        return $this->orderService->receiveOrderItem($orderItem);
    }

    public function afterserviceEnd($type, OrderItem $orderItem)
    {

        if ($type == '교환') {
            $this->orderService->endExchangeOrderItem($orderItem);
        }

        if ($type == '환불') {
            $this->orderService->endRefundOrderItem($orderItem);
        }

        if (get_class($orderItem->forcedSellType()) == 'Xpressengine\Plugins\XeroCommerce\Models\Product') {
            if($orderItem->forcedSellType()->trashed()){
                return route('xero_commerce::setting.product.index');
            }
            return route('xero_commerce::setting.product.show', [
                'productId' => $orderItem->sellType->id
            ]);
        } else {
            return route('xero_commerce::setting.product.index');
        }
    }
}
