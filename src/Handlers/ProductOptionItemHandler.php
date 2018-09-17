<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class ProductOptionItemHandler
{
    public function store(array $args)
    {
        $newProductOptionItem = new ProductOptionItem();

        $newProductOptionItem->fill($args);

        $newProductOptionItem->save();
    }
}
