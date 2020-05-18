<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Xpressengine\Database\Eloquent\Builder;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Models\UserInfo;

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

    /**
     * 주문목록
     * @param int $page
     * @param int $count
     * @param Collection|null $param
     * @return mixed
     */
    public function list($page = 1, $count = 10, Collection $param = null)
    {
        $condition = null;
        if($param) {
            $condition = function(Builder $query) use ($param) {
                if($orderNo = $param->get('order_no')) {
                    $query->where('order_no', 'LIKE' , "%$orderNo%");
                }
                if($code = $param->get('code')) {
                    $query->where('code', $code);
                }
                if(($from = $param->get('from_date')) && ($to = $param->get('to_date'))) {
                    $query->whereBetween('created_at', [$from, (new Carbon($to))->endOfDay()]);
                }
                if($shipNo = $param->get('ship_no')) {
                    $query->whereHas('items.shipment', function($q) use ($shipNo) {
                        $q->where('ship_no', 'LIKE', "%$shipNo%");
                    });
                }
                $userName = $param->get('user_name');
                $userPhone = $param->get('user_phone');
                if($userName || $userPhone) {
                    $query->whereHas('userInfo', function($q) use ($userName, $userPhone) {
                        if($userName) $q->where('name', 'LIKE', "%$userName%");
                        if($userPhone) $q->where('phone', 'LIKE', "%$userPhone%");
                    });
                }
                $recvName = $param->get('recv_name');
                $recvPhone = $param->get('recv_phone');
                if($recvName || $recvPhone) {
                    $query->whereHas('items.shipment', function($q) use ($recvName, $recvPhone) {
                        $q->where('recv_name', 'LIKE', "%$recvName%");
                        $q->where('recv_phone', 'LIKE', "%$recvPhone%");
                    });
                }
            };
        }
        return $this->orderHandler->getOrderList($page, $count, $condition);
    }

    public function getOrderItemList()
    {
        return $this->orderHandler->getOrderItemList(new Order(), function ($query) {
            $query->where('code', '!=', Order::TEMP);
        });
    }

    public function shipmentProcess(Request $request)
    {
        foreach ($request->shipment as $shipment) {
            $this->orderHandler->shipNoRegister(OrderItem::find($shipment['id']), $shipment['no']);
        }
    }

    public function shipmentComplete(Request $request)
    {
        foreach ($request->shipment as $shipment) {
            $this->orderHandler->completeShipment(OrderItem::find($shipment));
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

        return $this->orderHandler->changeOrderItem($orderItem, OrderItem::CODE_EXCHANGED);
    }

    public function endRefundOrderItem(OrderItem $orderItem)
    {
        $this->orderHandler->endOrderAfterService($orderItem);

        return $this->orderHandler->changeOrderItem($orderItem, OrderItem::CODE_REFUNDED);
    }

    /**
     * 주문 하나를 가져오는 함수
     * @param $orderId
     * @return Order|null
     */
    public function getOrder($orderId)
    {
        $order = $this->orderHandler->getOrder($orderId);
        $order->status = $order->getStatus();
        $order->load('payment', 'userInfo');

        return $order;
    }
}
