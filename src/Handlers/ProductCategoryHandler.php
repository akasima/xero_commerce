<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

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
}
