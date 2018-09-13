<?php

namespace Xpressengine\Plugins\XeroStore\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Handlers\ProductHandler;

class OrderSettingService
{
    /** @var ProductHandler $handler */
    protected $orderHandler;

    public function __construct()
    {
        $this->orderHandler = app('xero_store.orderHandler');
    }
}
