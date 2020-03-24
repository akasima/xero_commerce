<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\Products;

use Xpressengine\Plugins\XeroCommerce\Models\Product;

/**
 * 묶음상품
 * 
 * @author darron1217
 *
 */
class BundleProduct extends Product
{

    protected static $singleTableType = 'bundle';
    
    /**
     * Product items of bundle
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany(BundleItem::class, 'bundle_product_id');
    }
    
}
