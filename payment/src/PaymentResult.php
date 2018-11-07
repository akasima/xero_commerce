<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 06/11/2018
 * Time: 2:28 PM
 */

namespace Xpressengine\XePlugin\XeroPay;


use Illuminate\Contracts\Support\Jsonable;

abstract class PaymentResult implements PaymentResponse, Jsonable
{
    abstract public function getReceipt();
}
