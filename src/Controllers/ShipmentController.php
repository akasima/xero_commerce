<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use XePresenter;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;

class ShipmentController extends XeroCommerceBasicController
{
    public function index()
    {
        return XePresenter::make('no_shipment');
    }

    public function store(OrderHandler $orderHandler, Request $request)
    {
        $item = $orderHandler->addUserAddress($request->all());

        return XePresenter::makeApi(
            ['item' => $item]
        );
    }
}
