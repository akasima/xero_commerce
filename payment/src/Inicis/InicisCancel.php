<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 01/11/2018
 * Time: 4:37 PM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use Xpressengine\XePlugin\XeroPay\Inicis\Libs\INILib;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;

class InicisCancel implements PaymentResponse
{
    private $lib;
    public function __construct(INILib $lib)
    {
        $this->lib=$lib;
    }

    public function success()
    {
        return $this->lib->GetResult('ResultCode') =='00';
    }

    public function fail()
    {
        return !$this->success();
    }

    public function msg()
    {
        return $this->lib->GetResult('ResultMsg');
    }

    public function getUniqueNo()
    {
        // TODO: Implement getUniqueNo() method.
    }

    public function getDateTime()
    {
        // TODO: Implement getDateTime() method.
    }

    public function getInfo()
    {
        return $this->lib;
    }

    public function getPayment()
    {
        // TODO: Implement getPayment() method.
    }
}
