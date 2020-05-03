<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class OrderAfterservice extends DynamicModel
{
    protected $table = 'xero_commerce__order_afterservices';

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }
}
