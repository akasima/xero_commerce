<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class CartGroup extends SellGroup
{
    protected $table = 'xero_commerce_cart_group';

    protected $casts = [
        'custom_values' => 'json'
    ];

    public $timestamps = false;

    function getCount()
    {
        return $this->count;
    }

    function setCount($count)
    {
        $this->count=$count;
    }

    public function sellSet()
    {
        return $this->belongsTo(Cart::class);
    }

    public function getCustomValues()
    {
        return $this->custom_values;
    }

    public function setCustomValues($customValues)
    {
        $this->custom_values = $customValues;
    }
}
