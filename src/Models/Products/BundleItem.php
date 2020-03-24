<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\Products;

use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

/**
 * 묶음상품 하위품목
 * 
 * @author darron1217
 *
 */
class BundleItem extends DynamicModel
{

    protected $table = 'xero_commerce_product_bundle_item';
    
    protected $fillable = [
        'product_id',
        'quantity',
        'option_values'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}
