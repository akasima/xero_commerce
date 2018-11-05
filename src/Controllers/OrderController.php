<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
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
        return \XePresenter::make('xero_commerce::views.order.dash', ['title' => '주문내역', 'dashboard' => $this->orderService->dashBoard()]);
    }

    public function list(Request $request)
    {
        $default = [
            'date' => [
                now()->subWeek()->toDateString(),
                now()->toDateString()
            ]
            ,
            'equal' => [
                'code' => 'all'
            ],
            'compare' => [

            ],
            'inGroup' => [

            ]
        ];
        if ($request->get('code')) $default['equal']['code'] = $request->get('code');
        $data = $this->orderService->orderList(1, 5, $default);
        return \XePresenter::make('xero_commerce::views.order.list',
            [
                'title' => '주문/배송조회',
                'list' => $data['data'],
                'paginate' => $data['paginate'],
                'default' => $default
            ]);
    }

    public function listJson(Request $request, $page = 1)
    {
        return $this->orderService->orderList($page, $request->count ?: 10, (array)$request->condition);
    }

    public function detail(Order $order)
    {
        return \XePresenter::make('xero_commerce::views.order.detail', ['title' => '주문상세', 'order' => $this->orderService->orderDetail($order)]);
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
        $order = $this->orderService->getOrderableOrder($request->order_id);
        $paymentService = new PaymentService();
        $paymentService->loadScript();
        return \XePresenter::make(
            'xero_commerce::views.order.register',
            ['title' => 'test',
                'agreements' => [
                    'purchase' => AgreementService::get('purchase'),
                    'privacy' => AgreementService::get('privacy'),
                    'thirdParty' => AgreementService::get('thirdParty')
                ],
                'order' => $order,
                'orderItems' => $this->orderService->orderItemList($order),
                'summary' => $this->orderService->summary($order),
                'payMethods' => $paymentService->methodList()
            ]
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

    public function afterService($as, Order $order, OrderItem $orderItem)
    {
        $paymentService = new PaymentService();
        $paymentService->loadScript();
        switch ($as) {
            case 'change':
                $type = '교환';
                break;
            case 'refund':
                $type = '환불';
                break;
            default:
                throwException(\HttpUrlException::class);
        }
        return \XePresenter::make('xero_commerce::views.order.as',
            [
                'type' => $type,
                'order' => $order,
                'item' => $orderItem->getJsonFormat(),
                'company' => DeliveryCompany::get(),
                'payMethods' => $paymentService->methodList()
            ]);
    }

    public function asRegister($type, OrderItem $orderItem, Request $request)
    {
        if ($type == '교환') $this->orderService->exchangeOrderItem($orderItem, $request);
        if ($type == '환불') $this->orderService->refundOrderItem($orderItem, $request);
    }

    public function cancelRegister(Order $order, Request $request){
        $this->orderService->cancel($order, $request);
    }

    public function cancelService(Order $order)
    {
        return \XePresenter::make('xero_commerce::views.order.cancel',
            [
                'order' => $order,
                'summary'=>$this->orderService->summary($order)
            ]);
    }

    public function cancel(Order $order, Request $request)
    {
        $this->orderService->cancel($order, $request);
        return redirect()->route('xero_commerce::order.list');
    }
}
