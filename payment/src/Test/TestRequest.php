<?php

namespace Xpressengine\XePlugin\XeroPay\Test;

use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\AbstractPaymentRequest;

class TestRequest extends AbstractPaymentRequest
{

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getMethod()
    {
        return Test::$methods[$this->request->methods];
    }

    public function getPrice()
    {
        return 0;
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    public function encrypt()
    {
        // TODO: Implement encrypt() method.
    }

    public function setForm()
    {
        return [
            'price'=>$this->getPrice(),
            'id'=>sha1('test'.rand(0,100).now()->toDateTimeString())
        ];
    }

    public function url()
    {
        return Test::$url;
    }
}