<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;

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
        return \XePresenter::make(
            'order.register',
            ['title' => 'test',
                'order' => $order,
                'summary' => $this->orderService->summary($order)]
        );
    }

    public function registerAgain(Order $order)
    {
        return \XePresenter::make(
            'order.register',
            ['title' => 'test',
                'order' => $order,
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

    public function success(Order $order)
    {
        $this->orderService->complete($order);
        return redirect()->route('xero_commerce::order.index');
    }
}
