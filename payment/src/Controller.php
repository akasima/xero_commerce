<?php
namespace Xpressengine\XePlugin\XeroPay;

use App\Facades\XeConfig;
use XeFrontend;
use XePresenter;
use App\Http\Controllers\Controller as BaseController;
use Xpressengine\Http\Request;

class Controller extends BaseController
{
    public $service ;

    public function __construct()
    {
        $this->service = new PaymentService();
    }

    public function index()
    {
        $title = 'XeroPay plugin';

        // set browser title
        XeFrontend::title($title);

        // load css file
        XeFrontend::css(Plugin::asset('assets/style.css'))->load();
        // output
        return XePresenter::make('xero_commerce::payment.views.index', ['title' => $title, 'pg'=>$this->service->getPg() ,'config'=>XeConfig::getOrNew('xero_pay')]);
    }

    public function setConfig(Request $request)
    {
        $pgconfig=[];
        foreach($this->service->getPg() as $id=>$class)
        {
            $pgconfig[$id]=$this->getInputs($request,$id);
        }
        XeConfig::set('xero_pay',['uses'=>$request->get('pg'), 'pg'=>$pgconfig]);
        return redirect()->route('xero_pay::index');
    }

    public function callback(Request $request)
    {
        return $this->service->execute($request);
    }

    public function formList(Request $request)
    {
        return $this->service->formatRequest($request);
    }

    public function setting()
    {
        return 'hi';
    }

    public function close()
    {
        return '<script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay_close.js" charset="UTF-8"></script>';
    }

    private function getInputs(Request $request, $componentId)
    {
        $inputs = [];
        foreach ($request->except(['_token', 'pg']) as $key => $val) {
            if (starts_with($key, $componentId . '_')) {
                $key = preg_replace('#^(' . $componentId . '_)#', '', $key);
                $inputs[$key] = $val;
            }
        }

        return $inputs;
    }

    public function vBank(Request $request)
    {
        $this->service->vBank($request);
    }
}
