<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class ProductCategory extends DynamicModel
{
    protected $table = 'xero_commerce_product_category';

    public $timestamps = false;

    protected $fillable = ['product_id', 'category_id'];
}
