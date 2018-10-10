<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Services\LabelService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductCategoryService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductOptionItemSettingService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSettingService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;
use Xpressengine\Tag\TagHandler;

class ProductController extends Controller
{
    /** @var ProductSettingService $productSettingService */
    protected $productSettingService;

    /** @var ProductOptionItemSettingService $productOptionItemSettingService */
    protected $productOptionItemSettingService;

    /** @var TagHandler $tagHandler */
    protected $tagHandler;

    /** @var LabelService $labelService */
    protected $labelService;

    public function __construct(TagHandler $tagHandler)
    {
        $this->productSettingService = new ProductSettingService();
        $this->productOptionItemSettingService = new ProductOptionItemSettingService();
        $this->tagHandler = $tagHandler;
        $this->labelService = new LabelService();
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

    public function store(Request $request, ProductCategoryService $productCategoryService)
    {
        $productId = $this->productSettingService->store($request);
        $this->productOptionItemSettingService->defaultOptionStore($request, $productId);
        $this->setTag($productId, $request);
        ProductSlugService::storeSlug($this->productSettingService->getProduct($productId), $request);
        $this->labelService->createProductLabel($productId, $request);
        $productCategoryService->createProductCategory($productId, $request);

        return redirect()->route('xero_commerce::setting.product.show', ['productId' => $productId]);
    }

    public function edit(Request $request, $productId)
    {
        $product = $this->productSettingService->getProduct($productId);

        $productLabelIds = [];
        foreach ($product->labels as $label) {
            $productLabelIds[] = $label->id;
        }

        $labels = Label::get();
        $badges = Badge::get();

        return XePresenter::make('xero_commerce::views.setting.product.edit', compact('product', 'productLabelIds', 'labels', 'badges'));
    }

    public function update(Request $request, $productId)
    {
        $this->productSettingService->update($request, $productId);
        $this->setTag($productId, $request);
        ProductSlugService::storeSlug($this->productSettingService->getProduct($productId), $request);
        $this->labelService->editProductLabel($productId, $request);

        return redirect()->route('xero_commerce::setting.product.show', ['productId' => $productId]);
    }

    public function remove(Request $request, $productId)
    {
        $this->productSettingService->remove($productId);
        $this->productOptionItemSettingService->removeProductOptionItems($productId);
        $this->unSetTag($productId);

        return redirect()->route('xero_commerce::setting.product.index');
    }

    public function getChildCategory(Request $request, ProductCategoryService $categoryService)
    {
        $parentId = $request->get('parentId');

        $childCategory = $categoryService->getChildCategory($parentId);

        return XePresenter::makeApi(['type' => 'success', 'categories' => $childCategory]);
    }

    private function setTag($productId, Request $request)
    {
        if ($request->has('_tags') === true) {
            $this->tagHandler->set($productId, $request->get('_tags'), 'xero_commerce');
        }
    }

    private function unSetTag($productId)
    {
        $tags = \XeTag::fetchByTaggable($productId);
        \XeTag::detach($productId, $tags);
    }
}
