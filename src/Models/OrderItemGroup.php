<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;


class OrderItemGroup extends SellGroup
{

    protected $table = 'xero_commerce_order_item_group';
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
        return $this->belongsTo(OrderItem::class);
    }
}