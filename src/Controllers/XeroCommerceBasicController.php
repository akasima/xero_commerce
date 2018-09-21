<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceModule;

class XeroCommerceBasicController extends Controller
{
    public function __construct()
    {
        \XePresenter::setSkinTargetId(XeroCommerceModule::getId());
    }
}
