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
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\LG\XPayClient\XPayClient;
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

    public function execute(Request $request)
    {
        $response = new LGResponse($request);
        if ($response->fail()) return '<script>alert("' . $response->msg() . '"); parent.closeIframe();</script>';
        $tx_id = $this->Gen_TX_ID($request->get('LGD_MID'));
        $res = $this->curlPost(LG::url(), [
            'LGD_MID' => $request->get('LGD_MID'),
            'LGD_TXNAME' => 'PaymentByKey',
            'LGD_PAYKEY' => $request->get('LGD_PAYKEY'),
            'LGD_TXID' => $tx_id,
            'LGD_AUTHCODE' => $this->Gen_Auth_Code($tx_id)
        ]);
        $res = new LGResult($res);
        if ($res->success()){

        }
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

    public function curlPost($url, $option)
    {
        $curl = curl_init($url);

        $post_array = array();
        if(is_array($option))
        {
            foreach($option as $key=>$value)
            {
                $post_array[] = urlencode($key) . "=" . urlencode($value);
            }

            $post_string = implode("&",$post_array);
        }
        else
        {
            $post_string = $option;
        }

        $option = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_string,
            CURLOPT_RETURNTRANSFER => true,
        ];

        //set various options

        // set user agent string for sending client version string
        curl_setopt($curl, CURLOPT_USERAGENT, "XPayClient (1.1.0.5/PHP)");

        //set error in case http return code bigger than 300
        //curl_setopt($this->ch, CURLOPT_FAILONERROR, true);

        // allow redirects
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // use gzip if possible
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

        // do not veryfy ssl
        // this is important for windows
        // as well for being able to access pages with non valid cert
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        // do not verify host name
        //
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION , 6 );
        curl_setopt_array($curl, $option);
        $res = curl_exec($curl);

        if (curl_errno($curl)) {
            abort(500, 'curl 요청 에러가 발생했습니다.');
        }
        curl_close($curl);
        return json_decode($res);
    }


}
