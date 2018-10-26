<?php
namespace Xpressengine\XePlugin\XeroPay\Test;

use Xpressengine\XePlugin\XeroPay\PaymentGate;

class Test extends PaymentGate
{
    static $methods = [
        '무통장입금(테스트)', '신용카드(테스트)'
    ];

    protected static $configItems = [
        'code' => '사용자아이디',
        'key' => '관리자토큰',
    ];

    static $url = '';

    static $handler;

    public function __construct()
    {
        self::$handler = new TestHandler();
    }
}
