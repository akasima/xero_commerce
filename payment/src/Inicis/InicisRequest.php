<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 25/10/2018
 * Time: 11:19 AM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use App\Facades\XeConfig;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\AbstractPaymentRequest;
use Xpressengine\XePlugin\XeroPay\Inicis\Libs\INIStdPayUtil;

class InicisRequest extends AbstractPaymentRequest
{
    private $util;

    public function __construct(Request $request)
    {
        $this->util = new INIStdPayUtil();
        parent::__construct($request);
    }

    public function getMethod()
    {
        return Inicis::$methods[$this->getRequest('method')];
    }

    public function getPrice()
    {
        // TODO: Implement getPrice() method.
    }

    public function encrypt()
    {
        // TODO: Implement encrypt() method.
    }

    public function setForm()
    {
        $form = [
            'version'=>Inicis::VERSION,
            'mid'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@inicis.mid'),
            'goodname'=>'test',
            'oid'=>'123',
            'price'=>'5000',
            'currency'=>'WON',
            'buyername'=>'홍길동',
            'buyertel'=>'01000000000',
            'buyeremail'=>'test@test.com',
            'timestamp'=>$this->util->getTimestamp(),
            'returnUrl'=>route('xero_pay::callback'),
            'closeUrl'=>route('xero_pay::close'),
            'mKey'=>$this->util->makeHash(XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@inicis.signKey'),'sha256'),
            'gopaymethod'=>'',
            'acceptmethod'=>'HPP(1):no_receipt:va_receipt:vbanknoreg(0):below1000',
            'cardcode'=>'',
            'payViewType'=>''
        ];
        $form['signature'] = $this->util->makeSignature(array_only($form,['oid', 'price', 'timestamp']));
        return $form;
    }

    private function signature (array $array)
    {
        $parseArray = [];
        foreach($array as $key => $val)
        {
            $parseArray[]=$key.'='.$val;
        }
        return hash('sha256',implode('&', $parseArray));
    }

    public function url()
    {
        // TODO: Implement url() method.
    }
}
