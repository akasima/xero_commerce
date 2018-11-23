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

    public function encrypt()
    {
        // TODO: Implement encrypt() method.
    }

    public function setForm()
    {
        $form = [
            'LGD_VERSION'=>LG::$version,
            'CST_PLATFORM'=>(LG::isTest()) ? 'test' :'service',
            'CST_MID'=>XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.id'),
            'LGD_OID'=>$this->payment->payable_unique_id,
            'LGD_AMOUNT'=>$this->payment->price,
            'LGD_BUYER'=>$this->request->get('user')['name'],
            'LGD_PRODUCTINFO'=>$this->payment->name,
            'LGD_TIMESTAMP'=>now()->format('YmdHis'),
            'LGD_RETURNURL'=>route('xero_pay::callback'),
            'LGD_CASNOTEURL'=>route('xero_pay::bank'),
            'LGD_WINDOW_TYPE'=>'iframe',
            'LGD_BUYERID'=>$this->request->get('user')['phone'],
            'LGD_BUYERIP'=>$this->request->ip(),
            'LGD_BUYEREMAIL'=>$this->request->get('user')['email'],
            'LGD_CUSTOM_PROCESSTYPE'=>'TWOTR',
            'LGD_CUSTOM_USABLEPAY'=>$this->getRequest('method'),
            'LGD_ENCODING'=>'UTF-8',
            'LGD_ENCODING_RETURNURL'=>'UTF-8',
            'LGD_ENCODING_NOTEURL'=>'UTF-8'
        ];
        $form['LGD_MID']=(LG::isTest()) ? 't'.$form['CST_MID'] :$form['CST_MID'];
        $form['LGD_HASHDATA'] = md5($form['LGD_MID'].$form['LGD_OID'].$form['LGD_AMOUNT'].$form['LGD_TIMESTAMP'].XeConfig::getOrNew('xero_pay')->get('pg.xero_pay/xero_pay@lg.mertKey'));
        return $form;
    }

    public function isPaidMethod()
    {
        return $this->getMethod()!='무통장입금';
    }
}
