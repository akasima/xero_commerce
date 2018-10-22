<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    protected $table='xero_commerce_delivery_company';

    const TYPE=[
        '택배',
        '자체배송',
        '퀵서비스',
        '수령'
    ];

    const LOGIS = 0;
    const SELF = 1;
    const QUICK = 2;
    const TAKE = 3;

    public function getType()
    {
        return self::TYPE[$this->type];
    }
}
