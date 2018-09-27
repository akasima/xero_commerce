<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class ProductSlug extends DynamicModel
{
    protected $table = 'xero_commerce_product_slug';

    public $timestamps = false;

    protected $fillable = [
        'target_id', 'slug', 'product_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'target_id');
    }
}
