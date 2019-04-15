<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use XePresenter;
use Xpressengine\Plugins\XeroCommerce\Services\AgreementService;
use Xpressengine\Plugins\XeroCommerce\Services\BadgeService;

class AgreementController extends SettingBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        return XePresenter::make('agreement.index');
    }

    public function edit(Request $request, $type)
    {
        $agreement = AgreementService::get($type);
        return XePresenter::make('agreement.edit',compact('agreement'));
    }

    public function update(Request $request, $type)
    {

        AgreementService::updateAgreement($request->all(),$type);
        return redirect()->route('xero_commerce::setting.agreement.index');
    }
}
