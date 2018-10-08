<?php

namespace Xpressengine\XePlugin\XeroPay\Test;

use Xpressengine\XePlugin\XeroPay\AbstractPaymentResponse;

class TestResponse extends AbstractPaymentResponse
{

    public function success()
    {
        return true;
    }

    public function getUniqueNo()
    {
        return '123';
    }

    public function getDateTime()
    {
        return now();
    }

    public function getInfo()
    {
        return 'This is test';
    }

    public function msg()
    {
        return 'This is Test';
    }
}