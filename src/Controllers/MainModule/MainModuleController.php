<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\MainModule;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceMainModule;

class MainModuleController extends Controller
{
    public function __construct()
    {
        //TODO 스킨 변경 가능 여부 확인
        \XePresenter::setSkinTargetId(XeroCommerceMainModule::getId());
    }

    public function index()
    {


        return \XePresenter::make('index');
    }
}
