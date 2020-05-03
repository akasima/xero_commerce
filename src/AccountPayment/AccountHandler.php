<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 15/04/2019
 * Time: 11:49 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\AccountPayment;


use App\Facades\XeConfig;
use App\Facades\XeFrontend;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\Models\Payment;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\PaymentRequest;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\PaymentResult;
use Xpressengine\XePlugin\XeroPay\Plugin;

class AccountHandler implements PaymentHandler
{

    /**
     * @return array
     */
    public function getMethodList()
    {
        return [
            'default'=>'계좌입금'
        ];
    }

    public function prepare()
    {
        XeFrontend::html('myscript')->content('
        <script>
        window.accountConfig = '.json_encode(XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_commerce@account')).'
        </script>
        ')->appendTo('head')->load();

        XeFrontend::js([
            Plugin::asset('assets/payment.js'),
            \Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/account.js')
        ])->appendTo('head')->load();
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function makePaymentRequest(Request $request, Payment $payment)
    {
        return new AccountRequest($request, $payment);
    }

    /**
     * @param Request $request
     * @return PaymentResponse;
     */
    public function getResponse(Request $request)
    {
        return new AccountResponse($request);
    }

    /**
     * @param Request $request
     * @param array $form
     * @return PaymentResult
     */
    public function getResult(Request $request)
    {
        return new AccountResult();
    }

    public function vBank(Request $request)
    {
        return '';
    }

    /**
     * @param Payment $payment
     * @param $reason
     * @return PaymentResponse
     */
    public function cancel(Payment $payment, $reason)
    {
        return new AccountResponse(request());
    }
}
