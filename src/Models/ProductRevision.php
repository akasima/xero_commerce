<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class ProductRevision extends Product
{
    const CREATED_AT = 'revision_created_at';
    const UPDATED_AT = 'revision_updated_at';
    const DELETED_AT = 'revision_deleted_at';

    protected $table = 'xero_commerce_products_revision';

    protected $fillable = ['revision_no', 'shop_id', 'product_code', 'name', 'original_price', 'sell_price',
        'discount_percentage', 'min_buy_count', 'max_buy_count', 'description', 'badge_id','tax_type', 'state_display',
        'state_deal', 'sub_name', 'shop_delivery_id', 'origin_deleted_at', 'origin_created_at', 'origin_updated_at'];
}
