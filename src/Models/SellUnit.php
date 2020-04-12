<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellUnit extends DynamicModel
{
    public function cartGroup()
    {
        return $this->morphMany(CartGroup::class, 'unit');
    }

    public function orderItemGroup()
    {
        return $this->morphMany(OrderItemGroup::class, 'unit');
    }

    abstract public function sellType();

    abstract public function getName();

    abstract public function getInfo();

    abstract public function getOriginalPrice();

    abstract public function getSellPrice();

    abstract public function getDisplayStateName();

    abstract public function getDealStateName();

    abstract public function isDisplay();

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'name' => $this->getName(),
            'combination_values' => $this->combination_values,
            'sell_price' => $this->getSellPrice(),
            'addition_price' => $this->addition_price,
            'state_display'=>$this->getDisplayStateName(),
            'state_deal'=>$this->getDealStateName(),
        ];
    }
}
