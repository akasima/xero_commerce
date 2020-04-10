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

    public function forcedSellUnit()
    {
        return $this->sellUnit()->withTrashed()->first();
    }

    abstract public function sellSet();

    public function getOriginalPrice()
    {
        return $this->getCount() * $this->forcedSellUnit()->getOriginalPrice();
    }

    public function getSellPrice()
    {
        return $this->getCount() * $this->forcedSellUnit()->getSellPrice();
    }

    public function getDiscountPrice()
    {
        return $this->getCount() * $this->forcedSellUnit()->getDiscountPrice();
    }

    abstract function getCustomValues();

    abstract function setCustomValues($customValues);

    public function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'unit' => $this->forcedSellUnit()->getJsonFormat(),
            'custom_values' => $this->getCustomValues(),
            'count' => $this->getCount(),
        ];
    }
}
