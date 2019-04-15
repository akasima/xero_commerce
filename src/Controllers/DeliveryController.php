<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use XePresenter;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;

class DeliveryController extends XeroCommerceBasicController
{
    public function index()
    {
        return XePresenter::make('no_delivery');
    }

    public function store(OrderHandler $orderHandler, Request $request)
    {
        $item = $orderHandler->addUserDelivery($request->all());

        return XePresenter::makeApi(
            ['item' => $item]
        );
    }
}
