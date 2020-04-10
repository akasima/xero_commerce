<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItemGroup extends SellGroup
{
    protected $table = 'xero_commerce_order_item_group';

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
        $this->count = $count;
    }

    public function sellSet()
    {
        return $this->belongsTo(OrderItem::class,'order_item_id');
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
