<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;


use Xpressengine\Database\Eloquent\DynamicModel;

class UserDelivery extends DynamicModel
{
    protected $table = 'xero_commerce_user_delivery';
    protected $guarded = [];
    public $timestamps = false;
}