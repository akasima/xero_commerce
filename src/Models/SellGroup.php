<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellGroup extends DynamicModel
{
    abstract function getCount();

    abstract function setCount($count);

    public function sellUnit()
    {
        return $this->morphTo('unit');
    }

    abstract public function sellSet();

    public function getOriginalPrice()
    {
        return $this->getCount() * $this->sellUnit->getOriginalPrice();
    }

    public function getSellPrice()
    {
        return $this->getCount() * $this->sellUnit->getSellPrice();
    }

    public function getDiscountPrice()
    {
        return $this->getCount() * $this->sellUnit->getDiscountPrice();
    }
}