<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends Goods
{
    protected $table='xero_commerce_option_order';

    public function getEachOriginalPrice()
    {
        return $this->original_price / $this->getCount();
    }

    public function getEachSellPrice()
    {
        return $this->sell_price / $this->getCount();
    }
}