<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;



use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\Orderable;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class CartService
{
    /**
     * @var CartHandler
     */
    protected $cartHandler;

    public function __construct()
    {
        $this->cartHandler = app('xero_commerce.cartHandler');
    }

    public function getList()
    {
        return $this->cartHandler->getCartList();
    }

    public function addList(Orderable $option, $count = 1)
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

    public function getCartsFromProduct($product_ids)
    {
        return $this->cartHandler->getCartListByProductIds($product_ids)->pluck('id');
    }

    public function getCartsById($cart_ids)
    {
        return $this->cartHandler->getCartListByCartIds($cart_ids);
    }

    public function summary()
    {
        return $this->cartHandler->cartSummary();
    }

}