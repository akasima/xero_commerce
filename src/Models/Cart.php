<?php

namespace Xpressengine\Plugins\XeroStore\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Cart extends DynamicModel
{
    protected $table = 'xero_store_cart';

    public function option()
    {
        return $this->belongsTo('Xpressengine\Plugins\XeroStore\Models\ProductOptionItem', 'option_id');
    }
}
