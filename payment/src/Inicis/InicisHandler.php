<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 25/10/2018
 * Time: 11:18 AM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use App\Facades\XeConfig;
use App\Facades\XeFrontend;
use Carbon\Carbon;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\Inicis\Libs\INIStdPayUtil;
use Xpressengine\XePlugin\XeroPay\Models\Payment;
use Xpressengine\XePlugin\XeroPay\PayCurl;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\PaymentRequest;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\Plugin;

class InicisHandler implements PaymentHandler
{
    private $util;

    public function __construct()
    {
        $this->util = new INIStdPayUtil();
    }

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
    public function makePaymentRequest(Request $request, Payment $payment)
    {
        return new InicisRequest($request, $payment);
    }

    /**
     * @param Request $request
     * @return PaymentResponse;
     */
    public function getResponse(Request $request)
    {
        return new InicisResponse($request);
    }

    /**
     * @param Request $request
     * @param array $form
     * @return PaymentResponse
     */
    public function getResult(Request $request)
    {
        $payment = Payment::find($request->get('orderNumber'));
        $form = [
            'mid'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@inicis.mid'),
            'authToken'=>$request->get('authToken'),
            'price'=>$payment->price,
            'timestamp'=>$this->util->getTimestamp(),
            'format'=>'JSON'
        ];
        $form['signature']=$this->util->makeSignature(array_only($form,['authToken','timestamp']));
        $data = PayCurl::post($request->get('authUrl'), [], $form);
        return new InicisResult($data);
    }

    public function vBank(Request $request)
    {
        $payment = Payment::find($request->get('no_oid'));
        $payment->target->vBank(Carbon::parse($request->get('dt_trans').$request->get('tm_trans')), $request->all());
    }
}
