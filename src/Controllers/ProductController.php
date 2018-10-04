<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceModule;
use Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;
use Xpressengine\Routing\InstanceConfig;

class ProductController extends XeroCommerceBasicController
{
    /** @var ProductService $productService */
    protected $productService;

    /** @var InstanceConfig */
    protected $instanceConfig;

    /** @var string $instanceId */
    private $instanceId;

    public function __construct()
    {
        parent::__construct();

        $this->productService = new ProductService();

        $this->instanceConfig = InstanceConfig::instance();
        $this->instanceId = $this->instanceConfig->getInstanceId();
    }

    public function index(Request $request)
    {
        $config = \XeConfig::get(sprintf('%s.%s', Plugin::getId(), $this->instanceId));
        $products = $this->productService->getProducts($request, $config);

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
