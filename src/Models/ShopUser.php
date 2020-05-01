<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class ShopUser extends DynamicModel
{
    protected $table = 'xero_commerce__shop_user';

    protected $fillable = ['shop_id', 'user_id'];

    public $timestamps = false;
}
