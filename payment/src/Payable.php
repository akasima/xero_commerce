<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 29/10/2018
 * Time: 11:39 AM
 */

namespace Xpressengine\XePlugin\XeroPay;


trait Payable
{
    function getId()
    {
        return $this->id;
    }

    function getType()
    {
        return self::class;
    }

    abstract function getPriceForPay();

    abstract function getNameForPay();

    function getPayInfo()
    {
        return [
            'id'=>$this->getId(),
            'type'=>$this->getType(),
            'price'=>$this->getPriceForPay(),
            'name'=>$this->getNameForPay()
        ];
    }
}
