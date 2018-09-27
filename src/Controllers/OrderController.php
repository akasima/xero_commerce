<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;

class OrderController extends XeroCommerceBasicController
{
    public $orderService;

    public function __construct()
    {
        parent::__construct();

        $this->orderService = new OrderService();
    }

    public function index()
    {
        return \XePresenter::make('order.dash', ['title' => '주문내역']);
    }

    public function register(Request $request)
    {
        $order = $this->orderService->order($request);
        return [
            'url' => route('xero_commerce::order.register.again', ['order' => $order->id])
        ];
    }

    public function registerAgain(Order $order)
    {
        return \XePresenter::make(
            'order.register',
            ['title' => 'test',
                'order' => $order,
                'orderItems' => $this->orderService->orderItemList($order),
                'summary' => $this->orderService->summary($order)]
        );
    }

    public function fail(Order $order)
    {
        return \XePresenter::make(
            'order.fail',
            [
                'title' => 'fail',
                'order' => $order
            ]
        );
    }

    public function success(Order $order, Request $request)
    {
        $this->orderService->pay($order, $request);
        return $this->orderService->complete($order, $request);
    }

    public function pay(Order $order)
    {

    }
}
