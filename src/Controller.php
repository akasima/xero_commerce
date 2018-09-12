<?php
namespace Xpressengine\Plugins\XeroStore;

use XeFrontend;
use XePresenter;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        $title = 'xero_store';

        // set browser title
        XeFrontend::title($title);

        // load css file
        XeFrontend::css(Plugin::asset('assets/style.css'))->load();

        // output
        return XePresenter::make('xero_store::views.index', ['title' => $title]);
    }
}
