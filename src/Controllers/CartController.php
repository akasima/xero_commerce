<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;

class CartController extends XeroCommerceBasicController
{
    public $cartService;

    public function __construct()
    {
        parent::__construct();

        $this->cartService = new CartService();
    }

    public function index()
    {
        return \XePresenter::make(
            'cart',
            [
                'title' => '장바구니',
                'carts' => $this->cartService->getList(),
                'summary' => $this->cartService->summary()
            ]
        );
    }

    public function draw(Cart $cart)
    {
        $this->cartService->drawList($cart);
        return redirect()->route('xero_commerce::cart.index');
    }
}
