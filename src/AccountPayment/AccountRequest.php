<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 15/04/2019
 * Time: 11:49 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\AccountPayment;


use Xpressengine\XePlugin\XeroPay\AbstractPaymentRequest;

class AccountRequest extends AbstractPaymentRequest
{

    public function getMethod()
    {
        return Account::$methods['default'];
    }

    public function encrypt()
    {
        return '';
    }

    public function setForm()
    {
        return [
            'paymentId'=>$this->payment->id,
            'url'=>route('xero_pay::callback')
        ];
    }

    public function isPaidMethod()
    {
        return false;
    }
}
