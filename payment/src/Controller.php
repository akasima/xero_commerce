<?php
namespace Xpressengine\XePlugin\XeroPay;

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
        return XePresenter::make('xero_pay::views.index', ['title' => $title]);
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

    }
}
