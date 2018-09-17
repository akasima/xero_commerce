<?php

namespace Xpressengine\Plugins\XeroStore\Services;


use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Handlers\CartHandler;
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

    public function order(Request $request)
    {
        return $this->orderHandler->register($this->getCartsFromRequest($request));
    }

    private function getCartsFromRequest(Request $request)
    {
        $cartService = new CartService();
        return $cartService->getCartsFromProduct($request->get('product_id'));
    }
}