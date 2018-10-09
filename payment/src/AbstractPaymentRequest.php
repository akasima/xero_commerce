<?php


namespace Xpressengine\XePlugin\XeroPay;


use Illuminate\Contracts\Support\Jsonable;

abstract class AbstractPaymentRequest implements PaymentRequest, Jsonable
{

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return \GuzzleHttp\json_encode($this->setForm());
    }

    public function validate()
    {
        return true;
    }
}