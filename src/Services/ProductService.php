<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;

class ProductService
{
    const DEFAULT_PAGINATION_COUNT = 9;

    /** @var ProductHandler $handler */
    protected $handler;

    /**
     * ProductService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.productHandler');
    }

    public function getProductsForWidget($request, $paginationCount = self::DEFAULT_PAGINATION_COUNT)
    {
        $query = $this->handler->getProductsQueryForWidget($request);

        $items = $query->paginate($paginationCount, ['*'], 'product_page')->appends($request->except('product_page'));

        return $items;
    }

    public function getProducts(Request $request, $config, $paginationCount = self::DEFAULT_PAGINATION_COUNT)
    {
        $query = $this->handler->getProductsQueryForModule($request, $config);

        $items = $query->paginate($paginationCount, ['*'], 'product_page')->appends($request->except('product_page'));

        return $items;
    }

    public function getProduct($productId)
    {
        $product = $this->handler->getProduct($productId);

        return $product;
    }
}
