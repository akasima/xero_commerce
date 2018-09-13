<?php

namespace Xpressengine\Plugins\XeroStore\Controllers;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return \XePresenter::make('xero_store::views.index', ['title' => 'xero_store']);
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
