<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class ShopDelivery extends DynamicModel
{
    protected $table='xero_commerce__shop_delivery_company';

    public function company()
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
