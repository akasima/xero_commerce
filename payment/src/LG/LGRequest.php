<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 9:40 AM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;


use App\Facades\XeConfig;
use Xpressengine\XePlugin\XeroPay\AbstractPaymentRequest;

class LGRequest extends AbstractPaymentRequest
{

    public function getMethod()
    {
        return LG::$methods[$this->getRequest('method')];
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
            'LGD_VERSION'=>LG::$version,
            'CST_PLATFORM'=>'test',
            'CST_MID'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.id'),
            'LGD_OID'=>'123',
            'LGD_AMOUNT'=>1,
            'LGD_BUYER'=>'홍길동',
            'LGD_PRODUCTINFO'=>'테스트',
            'LGD_TIMESTAMP'=>now()->format('YmdHis'),
            'LGD_RETURNURL'=>route('xero_pay::callback'),
            'LGD_CASNOTEURL'=>route('xero_pay::close'),
            'LGD_RESPCODE'=>"",
            'LGD_RESPMSG'=>"",
            'LGD_PAYKEY'=>"",
            'LGD_WINDOW_TYPE'=>'iframe',
            'LGD_WINDOW_VER'=>'2.5',
            'LGD_BUYERID'=>'test',
            'LGD_BUYERIP'=>'127.0.0.1',
            'LGD_BUYEREMAIL'=>'test@test.com',
            'LGD_CUSTOM_SKIN'=>'red',
            'LGD_CUSTOM_PROCESSTYPE'=>'TWOTR',
            'LGD_CUSTOM_SWITCHINGTYPE'=>'IFRAME',
            'LGD_ENCODING'=>'UTF-8',
            'LGD_ENCODING_RETURNURL'=>'UTF-8'
        ];
        $form['LGD_MID']='t'.$form['CST_MID'];
        $form['LGD_HASHDATA'] = md5($form['LGD_MID'].$form['LGD_OID'].$form['LGD_AMOUNT'].$form['LGD_TIMESTAMP'].XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.mertKey'));
        return $form;
    }

    public function url()
    {
        // TODO: Implement url() method.
    }
}
