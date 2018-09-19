<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends OrderSet
{
    protected $table='xero_commerce_order_item';

    const EXCHANGING = 1;
    const EXCHANGED = 2;
    const REFUNDING = 3;
    const REFUNDED = 4;

    public function getEachOriginalPrice()
    {
        return $this->original_price / $this->getCount();
    }

    public function getEachSellPrice()
    {
        return $this->sell_price / $this->getCount();
    }

    public function delivery()
    {
        return $this->hasOne(OrderDelivery::class);
    }
}