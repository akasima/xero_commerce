<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class ProductOptionItemRevision extends ProductOptionItem
{
    const CREATED_AT = 'revision_created_at';
    const UPDATED_AT = 'revision_updated_at';
    const DELETED_AT = 'revision_deleted_at';

    protected $table = 'xero_commerce__product_variant_revisions';

    protected $fillable = ['revision_no', 'id', 'product_id', 'option_type', 'name', 'addition_price', 'stock',
        'alert_stock', 'state_display', 'state_deal', 'origin_deleted_at', 'origin_created_at', 'origin_updated_at'];
}
