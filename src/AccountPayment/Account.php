<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 15/04/2019
 * Time: 11:49 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\AccountPayment;


use Xpressengine\XePlugin\XeroPay\PaymentGate;

class Account extends PaymentGate
{

    public static function url()
    {
        return '';
    }

    static $methods = [
        'default'=>'계좌입금'
    ];

    static $handler = AccountHandler::class;

    protected static $configItems = [
        'AccountNo' => '계좌번호',
        'Host' => '입금주명',
        'Msg' => '추가 메세지',
    ];
}
