<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 15/04/2019
 * Time: 11:50 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\AccountPayment;


use App\Facades\XeConfig;
use Xpressengine\XePlugin\XeroPay\PaymentResult;

class AccountResult extends PaymentResult
{

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {

        return json_encode([]);
    }

    public function success()
    {
        return true;
    }

    public function fail()
    {
        return !$this->success();
    }

    public function msg()
    {
        return '';
    }

    public function getUniqueNo()
    {
        return '';
    }

    public function getDateTime()
    {
        return '';
    }

    public function getInfo()
    {
        return '';
    }

    public function getPayment()
    {
        return 'default';
    }

    public function getReceipt()
    {
        return json_encode([
            '입금계좌'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_commerce@account.AccountNo'),
            '예금주'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_commerce@account.Host'),
            '추가사항'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_commerce@account.Msg')
        ]);
    }
}
