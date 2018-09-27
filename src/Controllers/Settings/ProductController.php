<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Services\ProductOptionItemSettingService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSettingService;
use Xpressengine\Tag\TagHandler;

class ProductController extends Controller
{
    /** @var ProductSettingService $productSettingService */
    protected $productSettingService;

    /** @var ProductOptionItemSettingService $productOptionItemSettingService */
    protected $productOptionItemSettingService;

    /** @var TagHandler */
    protected $tagHandler;

    public function __construct(TagHandler $tagHandler)
    {
        $this->productSettingService = new ProductSettingService();
        $this->productOptionItemSettingService = new ProductOptionItemSettingService();
        $this->tagHandler = $tagHandler;
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

        return XePresenter::make('xero_commerce::views.setting.product.show', compact('product'));
    }

    public function create()
    {
        $displayStates = Product::getDisplayStates();
        $dealStates = Product::getDealStates();

        return XePresenter::make('xero_commerce::views.setting.product.create', compact('displayStates', 'dealStates'));
    }

    public function store(Request $request)
    {
        $productId = $this->productSettingService->store($request);
        $this->productOptionItemSettingService->defaultOptionStore($request, $productId);
        $this->setTag($productId, $request);

        return redirect()->route('xero_commerce::setting.product.show', ['productId' => $productId]);
    }

    public function edit(Request $request, $productId)
    {
        $product = $this->productSettingService->getProduct($productId);

        $displayStates = Product::getDisplayStates();
        $dealStates = Product::getDealStates();

        return XePresenter::make('xero_commerce::views.setting.product.edit', compact('product', 'displayStates', 'dealStates'));
    }

    public function update(Request $request, $productId)
    {
        $this->productSettingService->update($request, $productId);
        $this->setTag($productId, $request);

        return redirect()->route('xero_commerce::setting.product.show', ['productId' => $productId]);
    }

    public function remove(Request $request, $productId)
    {
        $this->productSettingService->remove($productId);
        $this->productOptionItemSettingService->removeProductOptionItems($productId);
        $this->unSetTag($productId);

        return redirect()->route('xero_commerce::setting.product.index');
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
