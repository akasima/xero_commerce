<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\Products;

use Xpressengine\Plugins\XeroCommerce\Models\Product;

/**
 * 기간제 상품 (숙박, 강연 등)
 * 
 * @author darron1217
 *
 */
class TimeProduct extends Product
{

    protected static $singleTableType = 'time';
    
}
