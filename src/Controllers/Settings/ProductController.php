<?php

namespace Xpressengine\Plugins\XeroStore\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Models\Product;
use Xpressengine\Plugins\XeroStore\Services\ProductOptionItemSettingService;
use Xpressengine\Plugins\XeroStore\Services\ProductSettingService;

class ProductController extends Controller
{
    /** @var ProductSettingService $productSettingService */
    protected $productSettingService;

    /** @var ProductOptionItemSettingService $productOptionItemSettingService */
    protected $productOptionItemSettingService;

    public function __construct()
    {
        $this->productSettingService = new ProductSettingService();
        $this->productOptionItemSettingService = new ProductOptionItemSettingService();
    }

    public function index(Request $request)
    {
        $displayStates = Product::getDisplayStates();
        $dealStates = Product::getDealStates();
        $products = $this->productSettingService->getProducts($request);

        return XePresenter::make(
            'xero_store::views.setting.product.index',
            compact('displayStates', 'dealStates', 'products')
        );
    }

    public function show(Request $request, $productId)
    {
        $product = $this->productSettingService->getProduct($productId);

        return XePresenter::make('xero_store::views.setting.product.show', compact('product'));
    }

    public function create()
    {
        $displayStates = Product::getDisplayStates();
        $dealStates = Product::getDealStates();

        return XePresenter::make('xero_store::views.setting.product.create', compact('displayStates', 'dealStates'));
    }

    public function store(Request $request)
    {
        $productId = $this->productSettingService->store($request);

        $this->productOptionItemSettingService->defaultOptionStore($request, $productId);

        return redirect()->route('xero_store::setting.product.show', ['productId' => $productId]);
    }
}
