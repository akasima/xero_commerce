<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Database\Eloquent\DynamicModel;

class ProductCategory extends DynamicModel
{
    protected $table = 'xero_commerce_product_category';

    public $timestamps = false;

    protected $fillable = ['product_id', 'category_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }

    public function category()
    {
        return $this->hasOne(CategoryItem::class, 'id', 'category_id');
    }
}
