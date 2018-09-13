<?php

namespace Xpressengine\Plugins\XeroStore\Events;

use Xpressengine\Plugins\XeroStore\Models\Product;

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
