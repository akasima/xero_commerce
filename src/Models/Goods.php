<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Goods extends DynamicModel
{
    public function orderable()
    {
        return $this->morphTo('orderable', 'orderable_type', 'orderable_id');
    }

    public function renderGoodsInfo()
    {
        $info = $this->orderable->getInfo();
        $info[1] .= ' / '.$this->getCount().' 개';
        return $info;
    }

    function getOptionList()
    {
        return $this->orderable->getOptionList();
    }

    function getDescription()
    {
        return $this->orderable->getDescription();
    }

    function setCount($count)
    {
        $this->count = $count;
    }

    function getCount()
    {
        return $this->count;
    }

    function getEachOriginalPrice()
    {
        return $this->orderable->getOriginalPrice();
    }

    function getEachSellPrice()
    {
        return $this->orderable->getSellPrice();
    }

    function getThumbnailSrc()
    {
        return $this->orderable->getThumbnailSrc();
    }

    public function getOriginalPrice()
    {
        return $this->getCount() * $this->getEachOriginalPrice();
    }

    public function getSellPrice()
    {
        return $this->getCount() * $this->getEachSellPrice();
    }

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function calculateFare($totalPrice)
    {
        return
            ($this->is_fare_free($totalPrice))
                ? 0
                : $this->orderable->getShop()->getDefaultDeliveryCompany()->pivot->delivery_fare;
    }

    public function is_fare_free($totalPrice)
    {
        return $totalPrice >= $this->orderable->getShop()->getDefaultDeliveryCompany()->pivot->up_to_free;
    }

}
