<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

/**
 * 상점별 배송회사 설정
 * Class ShopCarrier
 * @package Xpressengine\Plugins\XeroCommerce\Models
 */
class ShopCarrier extends DynamicModel
{
    protected $table='xero_commerce__shop_carrier';

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
