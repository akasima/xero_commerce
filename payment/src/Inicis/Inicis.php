<?php
namespace Xpressengine\XePlugin\XeroPay\Inicis;

use Xpressengine\XePlugin\XeroPay\PaymentGate;

class Inicis extends PaymentGate
{
    const VERSION='1.0';


    static $handler = InicisHandler::class;

    static $methods = [
        'Card'=>'신용카드',
        'DirectBank'=>'실시간계좌이체',
        'HPP'=>'핸드폰결제',
        'VBank'=>'무통장입금',
        'GiftCard'=>'상품권'
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
