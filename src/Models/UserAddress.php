<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class UserAddress extends DynamicModel
{
    protected $table = 'xero_commerce__user_addresses';

    protected $guarded = [];

    public $timestamps = false;
}
