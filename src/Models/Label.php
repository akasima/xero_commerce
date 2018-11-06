<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Label extends DynamicModel
{
    protected $table = 'xero_commerce_label';

    public $timestamps = false;

    protected $fillable = ['name', 'eng_name', 'background_color', 'text_color'];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'xero_commerce_product_label', 'label_id', 'product_id');
    }
}
