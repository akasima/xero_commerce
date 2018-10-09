<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\AgreementService;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;
use Xpressengine\XePlugin\XeroPay\PaymentService;

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
        return \XePresenter::make('order.dash', ['title' => '주문내역', 'dashboard' => $this->orderService->dashBoard()]);
    }

    public function list(Request $request)
    {
        return \XePresenter::make('order.list', ['title' => '주문/배송조회', 'list' => $this->listJson( $request )]);
    }

    public function listJson(Request $request ,$page = 1)
    {
        return $this->orderService->orderList($page, $request->count ? : 10, (array) $request->condition);
    }

    public function detail(Order $order)
    {
        return \XePresenter::make('order.detail', ['title' => '주문상세', 'order' => $this->orderService->orderDetail($order)]);
    }

    public function register(Request $request)
    {
        $order = $this->orderService->order($request);
        return [
            'url' => route('xero_commerce::order.register.again'),
            'order_id' => $order->id
        ];
    }

    public function registerAgain(Request $request)
    {
        $order = Order::find($request->order_id);
        $paymentService = new PaymentService();
        $paymentService->loadScript();
        return \XePresenter::make(
            'order.register',
            ['title' => 'test',
                'agreements' => [
                    'purchase' => AgreementService::get('purchase'),
                    'privacy' => AgreementService::get('privacy'),
                    'thirdParty' => AgreementService::get('thirdParty')
                ],
                'order' => $order,
                'orderItems' => $this->orderService->orderItemList($order),
                'summary' => $this->orderService->summary($order),
                'payMethods'=>$paymentService->methodList()
            ]
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

    public function success(Request $request, Order $order)
    {
        $this->orderService->pay($order, $request);
        $cartService = new CartService();
        $cartService->drawList(Cart::where('order_id', $order->id)->pluck('id'));
        return $this->orderService->complete($order, $request);
    }

    public function pay(Order $order, Request $request)
    {
        return $this->orderService->pay($order, $request);
    }

    public function afterService()
    {
        return \XePresenter::make('order.as', ['title' => '주문내역']);
    }
}
