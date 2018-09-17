<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
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
        return \XePresenter::make('xero_store::views.index', ['title' => 'test']);
    }

    public function register(Request $request)
    {
        $order = $this->orderService->order($request);
        return \XePresenter::make('xero_store::views.register', ['title'=>'test', 'order'=>$order]);
    }
}
