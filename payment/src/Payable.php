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
    abstract function getId();

    abstract function getPrice();
}
