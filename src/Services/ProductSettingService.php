<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;

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
     *
     * @return \Xpressengine\Plugins\XeroCommerce\Models\Product
     */
    public function getProduct($productId)
    {
        $product = $this->productHandler->getProduct($productId);

        return $product;
    }

    /**
     * @param Product $product product
     *
     * @return array
     */
    public function getProductOptionArrays($product)
    {
        $options = [];
        /** @var ProductOptionItem $option
         */
        foreach ($product->productOption as $option) {
            $optionData['id'] = $option->id;
            $optionData['option_type_name'] = $option->getOptionTypeName();
            $optionData['name'] = $option->name;
            $optionData['addition_price'] = number_format($option->addition_price);
            $optionData['sell_price'] = number_format($option->getSellPrice());
            $optionData['stock'] = number_format($option->stock);
            $optionData['alert_stock'] = number_format($option->alert_stock);
            $optionData['state_display'] = $option->getOptionDisplayStateName();
            $optionData['state_deal'] = $option->getOptionDealStateName();

            $options[] = $optionData;
        }

        return $options;
    }

    /**
     * @param Request $request request
     *
     * @return Product
     */
    public function getProducts(Request $request)
    {
        $query = $this->productHandler->getProductsQueryForSetting($request);

        $products = $query->get();

        return $products;
    }

    /**
     * @param Request $request request
     *
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
