<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Facades\XeFrontend;
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

    public function dash()
    {
        XeFrontend::js('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js')->load();
        return \XePresenter::make('xero_commerce::views.setting.order.dash', [
            'title' => 'xero_commerce',
            'dash' => $this->orderService->dashBoard()
        ]);
    }

    public function list()
    {

    }

    public function payment()
    {

    }

    public function delivery()
    {
        return \XePresenter::make('xero_commerce::views.setting.order.delivery', [
            'title' => 'xero_commerce'
        ]);
    }

    public function buyOption()
    {

    }

    public function buyList()
    {

    }
}
