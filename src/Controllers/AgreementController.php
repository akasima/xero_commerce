<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Facades\XePresenter;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
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

        UserInfo::updateOrcreate([
            'user_id'=>Auth::id()
        ],[
            'name'=>$request->name,
            'phone'=>$request->phone
        ]);

        return redirect()->intended();
    }

    public function saveOrderAgree(Request $request, Order $order)
    {
        AgreementService::orderAgree($order, $request->get('id'));
    }

    public function cancelOrderAgree(Request $request, Order $order)
    {
        AgreementService::orderDenied($order, $request->get('id'));
    }
}
