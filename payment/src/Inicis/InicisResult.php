<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 2:16 PM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use Illuminate\Contracts\Support\Jsonable;
use Xpressengine\XePlugin\XeroPay\Models\Payment;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;

class InicisResult implements PaymentResponse, Jsonable
{
    private $arr;
    public function __construct($arr)
    {
        $this->arr= $arr;
    }

    public function success()
    {
        return $this->arr->resultCode=='0000';
    }

    public function msg()
    {
        return $this->arr->resultMsg;
    }

    public function getUniqueNo()
    {
        return $this->arr->tid;
    }

    public function getDateTime()
    {
        return $this->arr->applDate.$this->arr->applTime;
    }

    public function getInfo()
    {
        return $this->arr;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->getInfo());
    }

    public function fail()
    {
        return !$this->success();
    }

    public function getPayment()
    {
        return Payment::find($this->arr->MOID);
    }
}
