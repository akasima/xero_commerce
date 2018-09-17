<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;


use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;

class OrderService
{
    /**
     * @var OrderHandler
     */
    protected $orderHandler;

    public function __construct()
    {
        $this->orderHandler = app('xero_store.orderHandler');
    }

    public function order(Request $request)
    {
        $this->orderHandler->register($this->getCartsFromRequest($request));
    }

    public function getCartsFromRequest(Request $request)
    {
        return $request->get('cart_id');
    }
}