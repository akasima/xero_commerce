<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Services\ShopService;
use Xpressengine\Plugins\XeroCommerce\Services\ShopUserService;

class ShopController extends Controller
{
    /** @var ShopService $shopService */
    protected $shopService;

    /** @var ShopUserService $shopUserService */
    protected $shopUserService;
    /**
     * ShopController constructor.
     */
    public function __construct()
    {
        $this->shopService = new ShopService();
        $this->shopUserService = new ShopUserService();
    }

    /**
     * @param Request $request request
     *
     * @return XePresenter
     */
    public function index(Request $request)
    {
        $shops = $this->shopService->getShops($request);

        return XePresenter::make('xero_commerce::views.setting.shop.index', compact('shops'));
    }

    /**
     * @return XePresenter
     */
    public function create()
    {
        $shopTypes = Shop::getShopTypes();

        return XePresenter::make('xero_commerce::views.setting.shop.create', compact('shopTypes'));
    }

    /**
     * @param Request $request request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $newShop = $this->shopService->create($request);
        $newShopUser = $this->shopUserService->create($request, $newShop->id);

        return redirect()->route('xero_commerce::setting.config.shop.index');
    }
}
