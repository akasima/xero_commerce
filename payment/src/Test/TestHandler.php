<?php

namespace Xpressengine\XePlugin\XeroPay\Test;

use App\Facades\XeFrontend;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\PaymentRequest;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\Plugin;

class TestHandler implements PaymentHandler
{

    /**
     * @return array
     */
    public function getMethodList()
    {
        return Test::$methods;
    }

    public function prepare()
    {
        XeFrontend::js([
            Plugin::asset('assets/payment.js'),
            Plugin::asset('assets/test.js')
        ])->appendTo('body')->load();
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function makePaymentRequest(Request $request)
    {
        return new TestRequest($request);
    }

    /**
     * @param Request $request
     * @return PaymentResponse
     */
    public function execute(Request $request)
    {
        return new TestResponse($request);
    }
}
