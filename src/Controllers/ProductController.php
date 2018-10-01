<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;

class ProductController extends XeroCommerceBasicController
{
    protected $productService;

    public function __construct()
    {
        parent::__construct();

        $this->productService = new ProductService();
    }

    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request);

        return \XePresenter::make('product.index', ['products' => $products]);
    }

    public function show(Request $request, $strSlug)
    {
        $productId = ProductSlugService::getProductId($strSlug);

        $product = $this->productService->getProduct($productId);

        if ($product == null) {
            return redirect()->route('xero_commerce::product.index')
                ->with('alert', ['type' => 'danger', 'message' => '존재하지 않는 상품입니다.']);
        }

        return \XePresenter::make('product.show', ['product' => $product]);
    }

    public function cartAdd(Request $request)
    {
        $cartService = new CartService();
        $cartService->addList($this->productService->make($request));
    }
}
