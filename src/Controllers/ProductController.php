<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;

class ProductController extends Controller
{
    protected $productService;
    public function index()
    {
        return \XePresenter::make('xero_commerce::views.index', ['title' => 'test']);
    }

    public function cartAdd(Request $request)
    {
        $cartService = new CartService();
        $cartService->addList($this->productService->make($request));
    }
}
