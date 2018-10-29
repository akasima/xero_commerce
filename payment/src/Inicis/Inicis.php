<?php
namespace Xpressengine\XePlugin\XeroPay\Inicis;

use Xpressengine\XePlugin\XeroPay\PaymentGate;

class Inicis extends PaymentGate
{
    const VERSION='1.0';


    static $handler = InicisHandler::class;

    static $methods = [
        '무통장입금', '신용카드'
    ];
    protected static $configItems = [
        'mid' => 'mid',
        'signKey' => 'signKey',
    ];

    public static function url()
    {
        return '';
    }
}
