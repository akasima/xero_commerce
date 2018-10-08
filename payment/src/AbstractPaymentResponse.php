<?php


namespace Xpressengine\XePlugin\XeroPay;


use Illuminate\Contracts\Support\Jsonable;

abstract class AbstractPaymentResponse implements PaymentResponse, Jsonable
{

    public function fail()
    {
        return !$this->success();
    }

    public function toJson($options = 0)
    {
        $array = [
            'status' => $this->success(),
            'info' => $this->getInfo(),
            'msg' => $this->msg()
        ];
        return \GuzzleHttp\json_encode($array);
    }
}