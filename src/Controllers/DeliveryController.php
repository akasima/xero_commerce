<?php


namespace Xpressengine\Plugins\XeroCommerce\Controllers;


use App\Facades\XePresenter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\UserInfo;
use Xpressengine\Plugins\XeroCommerce\Services\AgreementService;

class DeliveryController extends Controller
{
    public function index ()
    {
        return XePresenter::make('xero_commerce::views.no_delivery');
    }
}
