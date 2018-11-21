<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;

class ProductCategoryHandler
{
    public function store($productId, array $categories)
    {
        foreach ($categories as $category) {
            $productCategory = new ProductCategory();

            $productCategory->product_id = $productId;
            $productCategory->category_id = $category;

            $productCategory->save();
        }
    }

    public function remove($productId)
    {
        ProductCategory::where('product_id', $productId)->delete();
    }

    public function getIds($productId)
    {
        return ProductCategory::where('product_id', $productId)->pluck('category_id')->all();
    }

    public function getLabels($productId)
    {
        $product = Product::find($productId);
        return $product->category()->pluck('word','xero_commerce_product_category.category_id')->map(function($item){
            return [
                'label'=>xe_trans($item->word),
                'id'=>$item->id
        ];
        });
    }
}
