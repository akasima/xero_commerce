<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Services\ShopService;

class ShopController extends Controller
{
    protected $shopService;

    /**
     * ShopController constructor.
     */
    public function __construct()
    {
        $this->shopService = new ShopService();
    }

    public function index(Request $request)
    {
        $shops = $this->shopService->getShops($request);

        return XePresenter::make('xero_commerce::views.setting.shop.index', compact('shops'));
    }

    public function create()
    {
        return XePresenter::make('xero_commerce::views.setting.shop.create');
    }

    public function store(Request $request)
    {
        $this->shopService->create($request);

        return redirect()->route('xero_commerce::setting.config.shop.index');
    }
}
