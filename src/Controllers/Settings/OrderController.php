<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function index()
    {
        return \XePresenter::make('xero_commerce::views.index', ['title' => 'xero_commerce']);
    }

    public function detail()
    {

    }

    public function shopList()
    {

    }

    public function payment()
    {

    }

    public function delivery()
    {

    }

    public function buyOption()
    {

    }

    public function buyList()
    {

    }
}
