<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class ProductOption extends DynamicModel
{
    protected $table = 'xero_commerce_product_option';

    protected $fillable = [
        'product_id',
        'display_type',
        'name',
        'values',
        'sort_order'
    ];

    protected $casts = [
        'values' => 'json',
    ];

}
