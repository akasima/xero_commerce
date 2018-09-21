<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;

class ProductController extends XeroCommerceBasicController
{
    protected $productService;

    public function index()
    {
        $skin = XeroCommerceDefaultSkin::class;

        return \XePresenter::make('index', ['title' => 'test', 'skin' => $skin]);
    }

    public function cartAdd(Request $request)
    {
        $cartService = new CartService();
        $cartService->addList($this->productService->make($request));
    }
}
