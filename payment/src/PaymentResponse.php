<?php


namespace Xpressengine\XePlugin\XeroPay;


interface PaymentResponse
{
    public function success();

    public function fail();

    public function msg();

    public function getUniqueNo();

    public function getDateTime();

    public function getInfo();
}