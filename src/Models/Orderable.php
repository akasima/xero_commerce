<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

abstract class Orderable extends DynamicModel
{
    public function goods()
    {
        return $this->morphMany(Goods::class, 'orderable');
    }

    abstract public function getInfo();

    abstract public function getOriginalPrice();

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice()-$this->getSellPrice();
    }

    abstract public function getSellPrice();

    abstract public function getOptionList();

    abstract public function getThumbnailSrc();

    abstract public function getDescription();

    abstract public function getStore();
}