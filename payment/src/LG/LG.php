<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 9:40 AM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;


use Xpressengine\XePlugin\XeroPay\PaymentGate;

class LG extends PaymentGate
{
    static $version = 'PHP_Non-ActiveX_Standard';
    static $methods = [
        '신용카드', '무통장입'
    ];

    protected static $configItems = [
        'id' => '상점아이디',
        'mertKey' => 'MertKey',
    ];
}
