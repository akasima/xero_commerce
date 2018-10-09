<?php


namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Http\Request;

class PaymentService
{

    /**
     * @var PaymentHandler
     */
    protected $handler;

    public function __construct()
    {
        $this->handler = app('xero_pay::paymentHandler');
    }

    public function loadScript()
    {
        $this->handler->prepare();
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function formatRequest(Request $request)
    {
        return $this->handler->makePaymentRequest($request);
    }

    /**
     * @param Request $request
     * @return PaymentResponse
     */
    public function execute(Request $request)
    {
        return $this->handler->execute($request);
    }

    public function methodList()
    {
        return $this->handler->getMethodList();
    }
}