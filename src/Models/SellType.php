<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;


use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellType extends DynamicModel
{
    abstract public function getName();

    abstract public function getInfo();

    abstract public function getFare();

    abstract public function getShop();

    abstract public function getThumbnailSrc();

    public function carts()
    {
        return $this->morphMany(Cart::class, 'type');
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'type');
    }

    abstract function sellUnits();

    /**
     * @return callable
     */
    abstract function getCountMethod();

    /**
     * @return callable
     */
    abstract function getOriginalPriceMethod();

    /**
     * @return callable
     */
    abstract function getSellPriceMethod();
}