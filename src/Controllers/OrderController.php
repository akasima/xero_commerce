<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;

class OrderController extends Controller
{
    public $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function index()
    {
        return \XePresenter::make( 'xero_commerce::views.order.dash', ['title' => '주문내역']);
    }

    public function register(Request $request)
    {
        $order = $this->orderService->order($request);
        return \XePresenter::make(
            'xero_commerce::views.order.register',
            ['title' => 'test',
                'order' => $order,
                'summary' => $this->orderService->summary($order)]
        );
    }

    public function registerAgain(Order $order)
    {
        return \XePresenter::make(
            'xero_commerce::views.order.register',
            ['title' => 'test',
                'order' => $order,
                'summary' => $this->orderService->summary($order)]
        );
    }

    public function fail(Order $order)
    {
        return \XePresenter::make(
            'xero_commerce::views.order.fail',
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
