<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

use Xpressengine\Plugins\XeroStore\Models\ProductOptionItem;

class ProductOptionItemHandler
{
    public function store(array $args)
    {
        $newProductOptionItem = new ProductOptionItem();

        $newProductOptionItem->fill($args);

        $newProductOptionItem->save();
    }
}
