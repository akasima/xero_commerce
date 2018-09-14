<?php

namespace Xpressengine\Plugins\XeroStore\Services;


use Xpressengine\Plugins\XeroStore\Handlers\OrderHandler;

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

    public function order(array $cart_ids)
    {
        $this->orderHandler->register($cart_ids);
    }
}