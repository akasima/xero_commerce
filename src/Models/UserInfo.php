<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use App\Facades\XeConfig;
use Xpressengine\Database\Eloquent\DynamicModel;

class UserInfo extends DynamicModel
{
    protected $table = 'xero_commerce_user';

    public $timestamps = false;

    static function by($user_id)
    {
        return self::where('user_id', $user_id)->first();
    }

    public function userDelivery()
    {
        return $this->hasMany(UserDelivery::class, 'user_id', 'user_id');
    }

    public function getLevel()
    {
        return 'VIP';
    }
}
