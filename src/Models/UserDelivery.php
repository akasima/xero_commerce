<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class UserDelivery extends DynamicModel
{
    protected $table = 'xero_commerce__user_delivery_addresses';

    protected $guarded = [];

    public $timestamps = false;
}
