<?php

namespace Xpressengine\Plugins\XeroStore\Services;



use Xpressengine\Plugins\XeroStore\Handlers\CartHandler;
use Xpressengine\Plugins\XeroStore\Models\Cart;
use Xpressengine\Plugins\XeroStore\Models\ProductOptionItem;

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

    public function getList()
    {
        return $this->cartHandler->getCartList();
    }

    public function addList(ProductOptionItem $option, $count = 1)
    {
        return $this->cartHandler->addCart($option, $count);
    }

    public function drawList(Cart $cart)
    {
        return $this->cartHandler->drawCart($cart->id);
    }

    public function resetList()
    {
        return $this->cartHandler->resetCart();
    }

}