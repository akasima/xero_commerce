<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceModule;

class XeroCommerceBasicController extends Controller
{
    public function __construct()
    {
        //TODO 스킨 변경 가능 여부 확인
        \XePresenter::setSkinTargetId(XeroCommerceModule::getId());
    }
}
