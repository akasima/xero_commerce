<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellSet extends DynamicModel
{
    const DELIVERY=[
        '선불'=>1,
        '착불'=>2
    ];
    abstract public function sellGroups();

    public function sellType()
    {
        return $this->morphTo('type');
    }

    /**
     * @return array
     */
    abstract public function renderInformation();

    function getCount()
    {
        $method = $this->sellType->getCountMethod();
        return $method($this->sellGroups);
    }

    function getDeliveryPay()
    {
        return array_search($this->delivery_pay,self::DELIVERY);
    }

    function getOriginalPrice()
    {
        $method = $this->sellType->getOriginalPriceMethod();
        return $method($this->sellGroups);
    }

    function getSellPrice()
    {
        $method = $this->sellType->getSellPriceMethod();
        return $method($this->sellGroups);
    }

    function getThumbnailSrc()
    {
        return $this->sellType->getThumbnailSrc();
    }

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function getFare()
    {
        if($this->getDeliveryPay()=='착불') return 0;
        return $this->sellType->getFare();
    }

    protected function renderSpanBr($var, $style = "")
    {
        return "<span style=\"{$style}\">{$var}</span> <br>";
    }

    public function addGroup(SellGroup $sellGroup)
    {
        return ($sellGroup->getCount() > 0) ? $this->sellGroups()->save($sellGroup) : $sellGroup->delete();
    }

    abstract function getJsonFormat ();

}
