<?php

namespace Xpressengine\Plugins\XeroStore\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroStore\Models\Product;

class ProductSettingService
{
    /** @var ProductHandler $handler */
    protected $productHandler;

    /**
     * ProductSettingService constructor.
     */
    public function __construct()
    {
        $this->productHandler = app('xero_store.productHandler');
    }

    /**
     * @param  integer $productId productId
     * @return \Xpressengine\Plugins\XeroStore\Models\Product
     */
    public function getProduct($productId)
    {
        $product = $this->productHandler->getProduct($productId);

        return $product;
    }

    /**
     * @param Request $request request
     * @return Product
     */
    public function getProducts(Request $request)
    {
        $query = $this->productHandler->getProductsQuery($request);

        $products = $query->get();

        return $products;
    }

    /**
     * @param Request $request request
     * @return int
     */
    public function store(Request $request)
    {
        $productArgs = $request->all();

        $newProductId = $this->productHandler->store($productArgs);

        return $newProductId;
    }
}
