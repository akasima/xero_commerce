<?php

namespace Xpressengine\Plugins\XeroStore\Services;



use Xpressengine\Plugins\XeroStore\Handlers\CartHandler;
use Xpressengine\Plugins\XeroStore\Tests\TestOption;

class CartService
{
    /**
     * @var CartHandler
     */
    protected $cartHandler;

    public function __construct()
    {
        $this->cartHandler = app('xero_store.cartHandler');
    }

    public function test()
    {
        return $this->cartHandler->getCartList();
    }

    public function addTest()
    {
        return $this->cartHandler->addCart(new TestOption());
    }

}