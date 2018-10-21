<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\MainModule;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceMainModule;
use Xpressengine\Routing\InstanceConfig;

class MainModuleController extends Controller
{
    protected $instanceId;

    public function __construct()
    {
        //TODO 스킨 변경 가능 여부 확인
        \XePresenter::setSkinTargetId(XeroCommerceMainModule::getId());

        $instanceConfig = InstanceConfig::instance();
        $this->instanceId = $instanceConfig->getInstanceId();
    }

    public function index()
    {


        return \XePresenter::make('index');
    }
}
