<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
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
        $shops = Shop::whereHas('users', function ($query) {
            $query->where('user.id', Auth::id());
        })->get();

        $categoryItems = $productCategoryService->getCategoryItems();

        return XePresenter::make('xero_commerce::views.setting.product.create',
            compact('labels', 'badges', 'categoryItems', 'shops'));
    }

    public function store(Request $request)
    {
        $this->customValidate($request);
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
        $this->customValidate($request);
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

    public function customValidate(Request $request)
    {
        Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('xero_commerce_products')->ignore($request->name, 'name'),
                'max:255'
            ],
            'sub_name' => 'required',
            'original_price' => 'required',
            'sell_price' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'shop_delivery_id' => 'required'
        ], [
            'name.required' => '이름 필드는 필수입니다.',
            'sub_name.required' => '간략 소개는 필수입니다.',
            'original_price.required' => '정상 가격은 필수입니다.',
            'sell_price.required' => '정상 가격은 필수입니다.',
            'description.required' => '상품소개는 필수입니다.',
            'stock.required' => '기초재고는 필수입니다.',
            'shop_delivery_id.required' => '배송사선택은 필수입니다.'
        ])->validate();
    }
}
