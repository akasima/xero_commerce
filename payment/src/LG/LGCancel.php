<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 01/11/2018
 * Time: 4:57 PM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;


use Xpressengine\XePlugin\XeroPay\PaymentResponse;

class LGCancel implements PaymentResponse
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function success()
    {
        return in_array($this->data->LGD_RESPCODE, [
            '0000',
            'RF00',
            'RF24',
            'XC01'
        ]);
    }

    public function fail()
    {
        return !$this->success();
    }

    public function msg()
    {
        return $this->data->LGD_RESPMSG;
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
        return $this->data;
    }

    public function getPayment()
    {
        // TODO: Implement getPayment() method.
    }
}
