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

        /** @var ProductOptionItem $option */
        foreach ($product->productOption as $option) {
            $optionData['id'] = $option->id;
            $optionData['option_type_name'] = $option->getOptionTypeName();
            $optionData['name'] = $option->name;
            $optionData['addition_price'] = $option->addition_price;
            $optionData['sell_price'] = $option->getSellPrice();
            $optionData['stock'] = $option->stock;
            $optionData['alert_stock'] = $option->alert_stock;
            $optionData['state_display'] = $option->state_display;
            $optionData['state_deal'] = $option->state_deal;
            $optionData['data']=$option;

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

        if (is_null($productArgs['discount_percentage'])) {
            $productArgs['discount_percentage'] = floor(($productArgs['original_price'] - $productArgs['sell_price']) * 10000/ $productArgs['original_price']) / 100;
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

    public function setPublish(bool $bool){
        $this->productHandler->setPublish($productId, $bool);
    }
}
