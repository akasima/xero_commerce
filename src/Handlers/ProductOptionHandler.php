<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\ProductOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionRevision;

class ProductOptionHandler
{
    public function store(array $args)
    {
        $newProductOption = new ProductOption();

        $newProductOption->fill($args);

        $newProductOption->save();

        return $newProductOption;
    }

    public function getOptionItem($productOptionId)
    {
        $item = ProductOption::where('id', $productOptionId)->first();

        return $item;
    }

    public function destroy(ProductOption $item)
    {
        $item->delete();
    }

    public function update(ProductOption $option, array $args)
    {
        $attributes = $option->getAttributes();
        foreach ($args as $key => $value) {
            if (array_key_exists($key, $attributes) === true) {
                $option->{$key} = $value;
            }
        }

        $option->save();

        return $option;
    }
}
