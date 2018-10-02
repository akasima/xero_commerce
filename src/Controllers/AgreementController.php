<?php


namespace Xpressengine\Plugins\XeroCommerce\Controllers;


use App\Facades\XePresenter;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\UserInfo;
use Xpressengine\Plugins\XeroCommerce\Services\AgreementService;

class AgreementController extends XeroCommerceBasicController
{
    public function contacts(Request $request)
    {
        return XePresenter::make('order.agreement', [
            'agreement' => AgreementService::get('contacts')
        ]);
    }

    public function saveContacts(Request $request)
    {
        AgreementService::userAgree($request->agreement_id);
        $userInfo = new UserInfo();
        $userInfo->name = $request->name;
        $userInfo->phone = $request->phone;
        $userInfo->user_id = Auth::id();
        $userInfo->save();
        return redirect()->intended();
    }
}