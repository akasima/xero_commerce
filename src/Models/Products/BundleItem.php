<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\Products;

use Xpressengine\Database\Eloquent\DynamicModel;

/**
 * 묶음상품 하위품목
 * 
 * @author darron1217
 *
 */
class BundleItem extends DynamicModel
{

    protected $table = 'xero_commerce_product_bundle_items';
    
    protected $fillable = [
        'product_id',
        'quantity',
        'option_values'
    ];
    
}
