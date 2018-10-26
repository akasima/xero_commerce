<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 23/10/2018
 * Time: 5:19 PM
 */

namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Plugin\ComponentInterface;
use Xpressengine\Plugin\ComponentTrait;

abstract class PaymentGate implements ComponentInterface
{
    use ComponentTrait;

    protected static $configItems = [];

    public static function configItems()
    {
        return static::$configItems;
    }

    abstract public static function url();
}
