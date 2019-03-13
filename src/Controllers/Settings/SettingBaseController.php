<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 27/11/2018
 * Time: 6:40 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;


use App\Http\Controllers\Controller;
use XeFrontend;

class SettingBaseController extends Controller
{
    public function __construct()
    {
        XeFrontend::css(
            \Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/css/settings.css')
        )->load();
        XeFrontend::js(
            \Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/settings.js')
        )->load();
        XeFrontend::bodyClass('xero-settings')->load();

        \XePresenter::setSettingsSkinTargetId(\Xpressengine\Plugins\XeroCommerce\Plugin::getId());
    }
}
