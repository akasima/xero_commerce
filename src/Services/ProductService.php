<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;

class ProductService
{
    /** @var ProductHandler $handler */
    protected $handler;

    /**
     * ProductService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.productHandler');
    }

    public function getProductsForWidget($request)
    {
        $query = $this->handler->getProductsQueryForWidget($request);

        return $query->get();
    }

    public function getProducts(Request $request, $config)
    {
        $query = $this->handler->getProductsQueryForModule($request, $config);

        return $query->get();
    }

    public function getProduct($productId)
    {
        $product = $this->handler->getProduct($productId);

        return $product;
    }
}
