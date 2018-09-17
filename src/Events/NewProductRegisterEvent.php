<?php

namespace Xpressengine\Plugins\XeroCommerce\Events;

use Xpressengine\Plugins\XeroCommerce\Models\Product;

class NewProductRegisterEvent
{
    /** @var Product $product */
    public $product;

    /**
     * NewProductRegisterEvent constructor.
     *
     * @param Product $product productItem
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
