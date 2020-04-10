<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Events\NewProductRegisterEvent;
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

        /** @var ProductOption $option */
        foreach ($product->options as $option) {
            $optionData['id'] = $option->id;
            $optionData['product_id'] = $option->product_id;
            $optionData['name'] = $option->name;
            $optionData['values'] = $option->values;
            $optionData['sort_order'] = $option->sort_order;

            $options[] = $optionData;
        }

        return $options;
    }

    /**
     * @param Product $product product
     *
     * @return array
     */
    public function getProductOptionItemArrays($product)
    {
        $optionItems = [];

        /** @var ProductOptionItem $optionItem */
        foreach ($product->optionItems as $optionItem) {
            $optionData['id'] = $optionItem->id;
            $optionData['name'] = $optionItem->name;
            $optionData['value_combination'] = $optionItem->value_combination;
            $optionData['addition_price'] = $optionItem->addition_price;
            $optionData['sell_price'] = $optionItem->getSellPrice();
            $optionData['product_price']=$product->sell_price;
            $optionData['stock'] = $optionItem->stock;
            $optionData['alert_stock'] = $optionItem->alert_stock;
            $optionData['state_display'] = $optionItem->state_display;
            $optionData['state_deal'] = $optionItem->state_deal;
            $optionData['data'] = $optionItem;

            $optionItems[] = $optionData;
        }

        return $optionItems;
    }

    /**
     * @param Product $product product
     *
     * @return array
     */
    public function getProductCustomOptionArrays($product)
    {
        $options = [];

        /** @var ProductCustomOption $option */
        foreach ($product->customOptions as $option) {
            $optionData['id'] = $option->id;
            $optionData['product_id'] = $option->product_id;
            $optionData['name'] = $option->name;
            $optionData['type'] = $option->type;
            $optionData['sort_order'] = $option->sort_order;
            $optionData['is_required'] = $option->is_required;
            $optionData['settings'] = $option->settings;

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

        if(empty($productArgs['buy_count_not_use'])===false){

            if ($productArgs['buy_count_not_use'] == 'on') {
                unset($productArgs['min_buy_count']);
                unset($productArgs['max_buy_count']);
            }
        }

        if (empty($productArgs['original_price'])===true) {
            $productArgs['original_price'] = $productArgs['sell_price'];
        }

        if (is_null($productArgs['discount_percentage']) && !is_null($productArgs['original_price']) && !is_null($productArgs['sell_price'])) {
            $orirginal_price = (int)str_replace(',','',$productArgs['original_price']);
            $sell_price = (int)str_replace(',','',$productArgs['sell_price']);
            $productArgs['discount_percentage'] = floor(($orirginal_price - $sell_price) * 10000/ $orirginal_price) / 100;
        }

        $newProductId = $this->productHandler->store($productArgs);

        \Event::dispatch(new NewProductRegisterEvent(Product::find($newProductId)));

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
