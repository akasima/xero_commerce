<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellSet extends DynamicModel
{
    const SHIPPING_FEE_TYPE = [
        '선불' => 1,
        '착불' => 2
    ];

    abstract public function sellGroups();

    public function sellType()
    {
        return $this->morphTo('type');
    }

    public function forcedSellType()
    {
        return $this->sellType()->withTrashed()->first();
    }

    /**
     * @return array
     */
    abstract public function renderInformation();

    function getCount()
    {
        $method = $this->forcedSellType()->getCountMethod();

        return $method($this->sellGroups);
    }

    function getShippingFee()
    {
        return array_search($this->shipping_fee, self::SHIPPING_FEE_TYPE);
    }

    function getOriginalPrice()
    {
        $method = $this->forcedSellType()->getOriginalPriceMethod();

        return $method($this->sellGroups);
    }

    function getSellPrice()
    {
        $method = $this->forcedSellType()->getSellPriceMethod();

        return $method($this->sellGroups);
    }

    function getThumbnailSrc()
    {
        return $this->forcedSellType()->getThumbnailSrc();
    }

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function getFare()
    {
        if ($this->getShippingFee() == '착불') {
            return 0;
        }
        if (!$this->forcedSellType()->isShipped()) {
            return 0;
        }

        return $this->forcedSellType()->getFare();
    }

    public function renderSpanBr($var, $style = "")
    {
        return "<span style=\"{$style}\">{$var}</span> <br>";
    }

    public function addGroup(SellGroup $sellGroup)
    {
        return ($sellGroup->getCount() > 0) ? $this->sellGroups()->save($sellGroup) : $sellGroup->delete();
    }

    abstract function getJsonFormat();
}
