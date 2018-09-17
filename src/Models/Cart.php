<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Cart extends DynamicModel
{
    protected $table = 'xero_commerce_cart';

    public function option()
    {
        return $this->belongsTo('Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem', 'option_id');
    }
}
