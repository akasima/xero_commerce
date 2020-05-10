<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\ExcelExport\ShipmentExport;
use Xpressengine\Plugins\XeroCommerce\ExcelExport\OrderCheckExport;
use Xpressengine\Plugins\XeroCommerce\ExcelImport\ShipmentImport;
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

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $count = $request->get('count', 10);

        $param = collect($request->all());
        if(!$param->has('from_date')) $param->put('from_date', Carbon::now()->addWeeks(-1)->format('Y-m-d'));
        if(!$param->has('to_date')) $param->put('to_date', Carbon::now()->format('Y-m-d'));

        $orders = $this->orderService->list($page, $count, $param);

        return \XePresenter::make('order.index', [
            'orders' => $orders,
            'fromDate' => $param->get('from_date'),
            'toDate' => $param->get('to_date')
        ]);
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

    public function show(Request $request, $orderId)
    {
        $order = $this->orderService->getOrder($orderId);

        return \XePresenter::make('order.show', [
            'title' => 'xero_commerce',
            'order' => $order
        ]);
    }

    public function payment()
    {

    }

    public function shipment()
    {
        return \XePresenter::make('order.shipment', [
            'title' => 'xero_commerce',
            'orderItems' => $this->orderService->getOrderItemList()
        ]);
    }

    public function shipmentExcelExport()
    {
        $orderItemList = $this->orderService->getOrderItemList();
        $orderItemList = $orderItemList->filter(function($item){return !$item['shipment']->ship_no;});
        return Excel::download(new ShipmentExport($orderItemList),now()->format('YmdHis').'.xlsx');
    }

	public function OrderCheckExcelExport()//02.06수정
    {
        $orderItemList = $this->orderService->getOrderItemList();
        $orderItemList = $orderItemList->filter(function($item){return !$item['shipment']->ship_no;});
        return Excel::download(new OrderCheckExport($orderItemList),now()->format('YmdHis').'.xlsx');
    }

    public function shipmentExcelImport(Request $request)
    {
        $handler = app('xero_commerce.orderHandler');
        $dataProcess = Excel::toArray(new ShipmentImport(),$request->file('shipment'));
        foreach($dataProcess[0] as $key=>$data){
            if($key && $data[3]){
                $handler->shipNoRegister(OrderItem::find((int)$data[0]),$data[3]);
            }
        }
        return redirect()->route('xero_commerce::setting.order.shipment');
    }

    public function registerShipment()
    {

    }

    public function processShipment(Request $request)
    {
        $this->orderService->shipmentProcess($request);
    }

    public function completeShipment(Request $request)
    {
        $this->orderService->shipmentComplete($request);
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

        if (get_class($orderItem->productWithTrashed()) == 'Xpressengine\Plugins\XeroCommerce\Models\Product') {
            if($orderItem->productWithTrashed()->trashed()){
                return route('xero_commerce::setting.product.index');
            }
            return route('xero_commerce::setting.product.show', [
                'productId' => $orderItem->product->id
            ]);
        } else {
            return route('xero_commerce::setting.product.index');
        }
    }
}
