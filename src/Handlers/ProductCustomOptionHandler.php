<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionRevision;

class ProductCustomOptionHandler
{
    public function store(array $args)
    {
        $class = ProductCustomOption::getSingleTableTypeMap()[$args['type']] ?: ProductCustomOption::class;
        $newOption = (new $class)->newInstance();
        $newOption->fill($args);
        $newOption->save();
        return $newOption;
    }

    public function update(ProductCustomOption $option, array $args)
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

    public function destroy(ProductCustomOption $option)
    {
        $option->delete();
    }
}
