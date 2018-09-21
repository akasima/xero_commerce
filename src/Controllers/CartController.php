<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Facades\XeFrontend;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;
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
        return \XePresenter::make('xero_commerce::views.cart.index');
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
        return view('xero_commerce::views.cart.change', ['cart'=>$cart]);
    }

    public function draw(Cart $cart)
    {
        $this->cartService->drawList($cart);
        return redirect()->route('xero_commerce::cart.index');
    }
}
