<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;


class CartGroup extends SellGroup
{

    protected $table = 'xero_commerce_cart_group';
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
}