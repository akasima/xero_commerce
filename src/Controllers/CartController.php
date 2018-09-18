<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;

class CartController extends Controller
{
    public $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function index()
    {
        return \XePresenter::make(
            'xero_commerce::views.cart',
            [
                'title' => '장바구니',
                'carts' => $this->cartService->getList(),
                'summary' => $this->cartService->summary()
            ]);
    }

    public function add(ProductOptionItem $optionItem)
    {
        $this->cartService->addList($optionItem);
        return redirect()->route('xero_commerce::cart.index');
    }

    public function draw(Cart $cart)
    {
        $this->cartService->drawList($cart);
        return redirect()->route('xero_commerce::cart.index');
    }
}
