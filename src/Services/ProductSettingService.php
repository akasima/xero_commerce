<?php

namespace Xpressengine\Plugins\XeroStore\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Handlers\ProductHandler;

class ProductSettingService
{
    /** @var ProductHandler $handler */
    protected $productHandler;

    public function __construct()
    {
        $this->productHandler = app('xero_store.productHandler');
    }

    public function getProduct($productId)
    {
        $product = $this->productHandler->getProduct($productId);

        return $product;
    }

    public function getProducts(Request $request)
    {
        $query = $this->productHandler->getProductsQuery($request);

        $products = $query->get();

        return $products;
    }

    public function store(Request $request)
    {
        $productArgs = $request->all();

        $newProductId = $this->productHandler->store($productArgs);

        return $newProductId;
    }
}
