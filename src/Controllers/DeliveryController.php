<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use XePresenter;

class DeliveryController extends XeroCommerceBasicController
{
    public function index()
    {
        return XePresenter::make('xero_commerce::views.no_delivery');
    }
}
