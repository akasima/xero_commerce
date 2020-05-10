<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\CartItem;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;
use Xpressengine\Plugins\XeroCommerce\Services\WishService;

class CartItemController extends XeroCommerceBasicController
{
    public $cartService;

    /**
     * CartItemController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cartService = new CartService();
    }

    public function index()
    {
        return \XePresenter::make(
            'cart.index',
            [
                'cartItems' => $this->cartService->getJsonList()
            ]
        );
    }

    /**
     * AJAX용 list
     * @return mixed
     */
    public function list()
    {
        return $this->cartService->getJsonList();
    }

    public function summary(Request $request)
    {
        return $this->cartService->summary($request);
    }

    public function change(CartItem $cartItem, Request $request)
    {
        return $this->cartService->change($cartItem, $request);
    }

    public function delete(CartItem $cartItem)
    {
        $this->cartService->delete($cartItem);
    }

    public function deleteList(Request $request)
    {
        $this->cartService->deleteList($request->get('item_ids'));
    }

    /**
     * 장바구니 여러품목 찜하기
     * @param Request $request
     */
    public function wishMany(Request $request)
    {
        $wishService = new WishService();

        $cartItems = $this->cartService->getList();
        $products = $cartItems->map(function(CartItem $cartItem){
            return $cartItem->product;
        });

        $wishService->storeMany($products);
    }
}
