<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 15/04/2019
 * Time: 11:50 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\AccountPayment;


use Xpressengine\XePlugin\XeroPay\AbstractPaymentResponse;
use Xpressengine\XePlugin\XeroPay\Models\Payment;

class AccountResponse extends AbstractPaymentResponse
{

    public function success()
    {
        return true;
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
        return Payment::find($this->request->get('paymentId'));
    }
}
