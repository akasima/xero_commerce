<?php
namespace Xpressengine\XePlugin\XeroPay\Inicis;

use App\Facades\XeConfig;
use Xpressengine\XePlugin\XeroPay\PaymentGate;

class Inicis extends PaymentGate
{
    const VERSION='1.0';


    static $handler = InicisHandler::class;

    static $methods = [
        'Card'=>'신용카드',
        'DirectBank'=>'실시간계좌이체',
        'HPP'=>'핸드폰결제',
        'VBank'=>'무통장입금',
        'GiftCard'=>'상품권'
    ];
    static $cards = [
        '01'=>'하나(외환)',
        '03'=>'롯데',
        '04'=>'현대',
        '06'=>'국민',
        '11'=>'BC',
        '12'=>'삼성',
        '14'=>'신한',
        '15'=>'한미',
        '16'=>'NH',
        '17'=>'하나카드',
        '21'=>'해외비자',
        '22'=>'해외마스터',
        '23'=>'JCB',
        '24'=>'해외아멕스',
        '25'=>'해외다이너스',
        '56'=>'카카오뱅크',
    ];

    static $banks = [
        '02'=>'한국산업은행',
        '03'=>'기업은행',
        '04'=>'국민은행',
        '05'=>'하나은행(외환)',
        '07'=>'수협중앙회',
        '11'=>'농협중앙회',
        '12'=>'단위농협',
        '16'=>'축협중앙회',
        '20'=>'우리은행',
        '21'=>'신한은행(조흥)',
        '23'=>'SC 제일은행',
        '25'=>'하나은행(서울)',
        '26'=>'구)신한은행',
        '27'=>'한국씨티은행(한미)',
        '31'=>'대구은행',
        '32'=>'부산은행',
        '34'=>'광주은행',
        '35'=>'제주은행',
        '37'=>'전북은행',
        '38'=>'강원은행',
    ];
    protected static $configItems = [
        'mid' => 'mid',
        'signKey' => 'signKey',
    ];

    public static function url()
    {
        return '';
    }

    public static function isTest()
    {
        return !is_null(XeConfig::get('xero_pay')->get('test'));
    }
}
