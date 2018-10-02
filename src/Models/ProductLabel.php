<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class ProductLabel extends DynamicModel
{
    protected $table = 'xero_commerce_product_label';

    public $timestamps = false;

    protected $fillable = ['product_id', 'label_id'];
}
