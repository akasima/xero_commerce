<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Services\StoreService;

class StoreController extends Controller
{
    protected $storeService;

    /**
     * StoreController constructor.
     */
    public function __construct()
    {
        $this->storeService = new StoreService();
    }

    public function index(Request $request)
    {
        $stores = $this->storeService->getStores($request);

        return XePresenter::make('xero_commerce::views.setting.store.index', compact('stores'));
    }

    public function create()
    {
        return XePresenter::make('xero_commerce::views.setting.store.create');
    }

    public function store(Request $request)
    {
        $this->storeService->create($request);

        return redirect()->route('xero_commerce::setting.config.store.index');
    }
}
