<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Services\ProductCategoryService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductManager;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSettingService;

class ProductController extends Controller
{
    /** @var ProductManager $productManager */
    protected $productManager;

    /** @var ProductSettingService $productSettingService */
    protected $productSettingService;

    public function __construct()
    {
        $this->productManager = new ProductManager();
        $this->productSettingService = new ProductSettingService();
    }

    public function index(Request $request)
    {
        $displayStates = Product::getDisplayStates();
        $dealStates = Product::getDealStates();
        $products = $this->productSettingService->getProducts($request);

        return XePresenter::make(
            'xero_commerce::views.setting.product.index',
            compact('displayStates', 'dealStates', 'products')
        );
    }

    public function show(Request $request, $productId)
    {
        $product = $this->productSettingService->getProduct($productId);
        $options = $this->productSettingService->getProductOptionArrays($product);

        return XePresenter::make('xero_commerce::views.setting.product.show', compact('product', 'options'));
    }

    public function create(ProductCategoryService $productCategoryService)
    {
        $labels = Label::get();
        $badges = Badge::get();

        $categoryItems = $productCategoryService->getCategoryItems();

        return XePresenter::make('xero_commerce::views.setting.product.create',
            compact('labels', 'badges', 'categoryItems'));
    }

    public function store(Request $request)
    {
        $productId = $this->productManager->store($request);

        return redirect()->route('xero_commerce::setting.product.show', ['productId' => $productId]);
    }

    public function edit(Request $request, $productId, ProductCategoryService $productCategoryService)
    {
        $product = $this->productSettingService->getProduct($productId);
        $categoryItems = $productCategoryService->getCategoryItems();


        $productLabelIds = [];
        foreach ($product->labels as $label) {
            $productLabelIds[] = $label->id;
        }

        $labels = Label::get();
        $badges = Badge::get();

        return XePresenter::make('xero_commerce::views.setting.product.edit', compact('product', 'productLabelIds', 'labels', 'badges', 'categoryItems'));
    }

    public function update(Request $request, $productId)
    {
        $this->productManager->update($request, $productId);

        return redirect()->route('xero_commerce::setting.product.show', ['productId' => $productId]);
    }

    public function remove(Request $request, $productId)
    {
        $this->productManager->remove($productId);

        return redirect()->route('xero_commerce::setting.product.index');
    }

    public function getChildCategory(Request $request, ProductCategoryService $categoryService)
    {
        $parentId = $request->get('parentId');

        $childCategory = $categoryService->getChildCategory($parentId);

        return XePresenter::makeApi(['type' => 'success', 'categories' => $childCategory]);
    }
}
