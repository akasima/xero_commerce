<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 9:40 AM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;


use App\Facades\XeFrontend;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\PaymentRequest;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\Plugin;

class LGHandler implements PaymentHandler
{

    /**
     * @return array
     */
    public function getMethodList()
    {
        return LG::$methods;
    }

    public function prepare()
    {
        XeFrontend::js([
            Plugin::asset('assets/payment.js'),
            Plugin::asset('assets/lg.js'),
            'https://pretest.uplus.co.kr:9443/xpay/js/xpay_crossplatform.js'
        ])->appendTo('head')->load();
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function makePaymentRequest(Request $request)
    {
        return new LGRequest($request);
    }

    /**
     * @param Request $request
     * @return PaymentResponse
     */
    public function execute(Request $request)
    {
        return new LGResponse($request);
    }

    public function callBack(Request $request)
    {
        // TODO: Implement callBack() method.
    }
}
