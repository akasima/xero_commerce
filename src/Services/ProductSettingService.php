<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Store;

class ProductSettingService
{
    /** @var ProductHandler $handler */
    protected $productHandler;

    /**
     * ProductSettingService constructor.
     */
    public function __construct()
    {
        $this->productHandler = app('xero_commerce.productHandler');
    }

    /**
     * @param  integer $productId productId
     * @return \Xpressengine\Plugins\XeroCommerce\Models\Product
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

        if (empty($productArgs['product_code']) === true) {
            $productArgs['product_code'] = time();
        }

        if ($productArgs['buy_count_not_use'] == 'on') {
            unset($productArgs['min_buy_count']);
            unset($productArgs['max_buy_count']);
        }

        //TODO 스토어 임시 하드코딩
        $productArgs['store_id'] = Store::where('store_name', 'basic store')->first()['id'];

        $newProductId = $this->productHandler->store($productArgs);

        return $newProductId;
    }

    public function update(Request $request, $productId)
    {
        $args = $request->all();
        $product = $this->productHandler->getProduct($productId);

        $this->productHandler->update($product, $args);
    }

    public function remove($productId)
    {
        $product = $this->productHandler->getProduct($productId);

        $this->productHandler->destroy($product);
    }
}
