<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 29/10/2018
 * Time: 11:39 AM
 */

namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\XePlugin\XeroPay\Models\Payment;

trait Payable
{
    abstract function getIdforPay();

    function getType()
    {
        return self::class;
    }

    abstract function getPriceForPay();

    abstract function getNameForPay();

    abstract function vBank($date, $info);

    function getPayInfo()
    {
        return [
            'id'=>$this->getId(),
            'type'=>$this->getType(),
            'price'=>$this->getPriceForPay(),
            'name'=>$this->getNameForPay()
        ];
    }

    function xeropay()
    {
        return $this->morphOne(Payment::class,'payable');
    }
}
