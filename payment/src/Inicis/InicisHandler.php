<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 25/10/2018
 * Time: 11:18 AM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use App\Facades\XeFrontend;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\PaymentRequest;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\Plugin;

class InicisHandler implements PaymentHandler
{

    /**
     * @return array
     */
    public function getMethodList()
    {
        return Inicis::$methods;
    }

    public function prepare()
    {
        XeFrontend::js([
            Plugin::asset('assets/payment.js'),
            Plugin::asset('assets/inicis.js'),
            'https://stgstdpay.inicis.com/stdjs/INIStdPay.js'
        ])->appendTo('head')->load();
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function makePaymentRequest(Request $request)
    {
        return new InicisRequest($request);
    }

    /**
     * @param Request $request
     * @return PaymentResponse
     */
    public function execute(Request $request)
    {
        return new InicisResponse($request);
    }

    public function callBack(Request $request)
    {

    }
}
