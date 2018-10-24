<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class OrderAfterservice extends DynamicModel
{
    protected $table = 'xero_commerce_order_afterservice';

    public $timestamps=false;

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function company()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }
}
