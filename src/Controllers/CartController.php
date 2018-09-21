<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;
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
            'cart.index'
        );
    }

    public function list()
    {
        return $this->cartService->getJsonList();
    }

    public function summary(Request $request)
    {
        return $this->cartService->summary($request);
    }

    public function change(Cart $cart)
    {
        return view('cart.change', ['cart'=>$cart]);
    }

    public function draw(Cart $cart)
    {
        $this->cartService->drawList($cart);
        return redirect()->route('cart.index');
    }
}
