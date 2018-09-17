<?php

namespace Xpressengine\Plugins\XeroStore\Controllers;

use App\Facades\XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Services\CartService;
use Xpressengine\Plugins\XeroStore\Services\OrderService;

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
        return XePresenter::make('xero_store::views.register', ['title'=>'test', 'order'=>$order]);
    }
}
