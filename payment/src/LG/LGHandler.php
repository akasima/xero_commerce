<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 9:40 AM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;


use App\Facades\XeConfig;
use App\Facades\XeFrontend;
use App\Facades\XePresenter;
use Carbon\Carbon;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\LG\XPayClient\XPayClient;
use Xpressengine\XePlugin\XeroPay\Models\Payment;
use Xpressengine\XePlugin\XeroPay\PayCurl;
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
            (LG::isTest()) ? 'https://pretest.uplus.co.kr:9443/xpay/js/xpay_crossplatform.js'
                : 'https://xpayvvip.uplus.co.kr/xpay/js/xpay_crossplatform.js'
        ])->appendTo('head')->load();
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function makePaymentRequest(Request $request, Payment $payment)
    {
        return new LGRequest($request, $payment);
    }

    public function getResponse(Request $request)
    {
        return new LGResponse($request);
    }

    public function getResult(Request $request)
    {
        $tx_id = $this->Gen_TX_ID($request->get('LGD_MID'));
        $data = PayCurl::post(LG::url(), [
            CURLOPT_USERAGENT => "XPayClient (1.1.0.5/PHP)",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => 'gzip, deflate',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6
        ], [
            'LGD_MID' => $request->get('LGD_MID'),
            'LGD_TXNAME' => 'PaymentByKey',
            'LGD_PAYKEY' => $request->get('LGD_PAYKEY'),
            'LGD_TXID' => $tx_id,
            'LGD_AUTHCODE' => $this->Gen_Auth_Code($tx_id)
        ]);
        return new LGResult($data);
    }


    //아래는 샘플에서 긁어와 수정한 소스들


    private function Get_Unique()
    {
        if (isset($_SESSION['tx_counter']))
            $_SESSION['tx_counter'] = $_SESSION['tx_counter'] + 1;
        else
            $_SESSION['tx_counter'] = 1;
//		$this->log("session id = ".session_id().$_SESSION['tx_counter'], LGD_LOG_FATAL);
        return session_id() . $_SESSION['tx_counter'] . $this->GenerateGUID();
    }

    private function Gen_TX_ID($MID)
    {
        $now = date("YmdHis");
        $header = $MID . "-" . '01' . $now;
        $tx_id = $header . sha1($header . $this->Get_Unique());
        return $tx_id;
    }

    private function Gen_Auth_Code($tx_id)
    {
        $auth_code = sha1($tx_id . XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.mertKey'));
        return $auth_code;
    }

    private function GenerateGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $uuid = $charid;
            return $uuid;
        }
    }

    public function vBank(Request $request)
    {
        $data = $request->all();
        $mertKey = XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.mertKey');
        $hash = md5($data['LGD_MID'] . $data['LGD_OID'] . $data['LGD_AMOUNT'] . $data['LGD_RESPCODE'] . $data['LGD_TIMESTAMP'] . $mertKey);
        if ($hash === $request->get('LGD_HASHDATA')) {
            $oid = str_replace('_', '-', $request->get('LGD_OID'));
            $payment = Payment::find($oid);
            $payment->target->vBank(Carbon::createFromTimestamp($request->get('LGD_TIMESTAMP')), $data);
        }
    }

    public function cancel(Payment $payment, $reason)
    {
        $info = json_decode($payment->info);
        $form = [
            'CST_MID' => XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.id'),
            'CST_PLATFORM' => (LG::isTest()) ? 'test' :'service',
            'LGD_TID' => $payment->transaction_id,
            'LGD_TXNAME' => 'Cancel'
        ];
        $form['LGD_MID']=(LG::isTest()) ? 't'.$form['CST_MID'] :$form['CST_MID'];
        $tx_id = $this->Gen_TX_ID($form['LGD_MID']);
        $form['LGD_TXID']=$tx_id;
        $form['LGD_AUTHCODE']=$this->Gen_Auth_Code($tx_id);
        $data = PayCurl::post(LG::url(), [
            CURLOPT_USERAGENT => "XPayClient (1.1.0.5/PHP)",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => 'gzip, deflate',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6
        ], $form);
        return new LGCancel($data);
    }
}
