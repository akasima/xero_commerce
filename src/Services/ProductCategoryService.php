<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Category\Models\Category;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class ProductCategoryService
{
    /** @var ProductCategoryHandler $handler */
    protected $handler;

    /**
     * ProductCategoryService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.productCategoryHandler');
    }

    public function getCategoryItems()
    {
        $pluginConfig = \XeConfig::get(Plugin::getId());

        $categoryItems = Category::find($pluginConfig->get('categoryId'))->getProgenitors();

        return $this->convertCategoryItemArray($categoryItems);
    }

    public function getChildCategory($parentId)
    {
        $categoryItems = CategoryItem::where('parent_id', $parentId)->get();

        return $this->convertCategoryItemArray($categoryItems);
    }

    public function getChildren(CategoryItem $categoryItem){
        return [
            'self'=>[
                'id'=>$categoryItem->id,
                'label'=>xe_trans($categoryItem->word),
                'url'=>$this->getRoute($categoryItem)
                ],
            'children'=>$categoryItem->getChildren()->map(function (CategoryItem $categoryItem){
                return $this->getChildren($categoryItem);
            })
        ];
    }

    private function getRoute(CategoryItem $categoryItem)
    {
        $config=\XeConfig::get('xero_commerce.mainModuleList');
        $instances = collect($config->get('moduleList'));
        $instance = $instances->first(function($instance_id)use($categoryItem){
            $config = \XeConfig::get('xero_commerce.'.$instance_id);
            return $config->get('categoryItemId') === $categoryItem->id;
        });
        if($instance)return instance_route('xero_commerce::product.index',[],$instance);
        return url(Plugin::XERO_COMMERCE_URL_PREFIX);
    }

    public function getCategoryTree()
    {
        $first = CategoryItem::whereNull('parent_id')->get();
        return $first->map(function(CategoryItem $categoryItem){
            return $this->getChildren($categoryItem);
        });
    }

    public function getProductCategoryTree($productId)
    {
        $productCateogryIds = $this->getProductCategory($productId);
        $productCateogrys=CategoryItem::find($productCateogryIds);
        $convertedCategoryItems=$productCateogrys->map(function(CategoryItem $categoryItem){
            $ancestors = $categoryItem->ancestors(false)->orderBy($categoryItem->getClosureTable().'.'.$categoryItem->getDepthName(),'desc')->get();
            return $ancestors->map(function (CategoryItem $categoryItem){
                return [
                    'id'=>$categoryItem->id,
                    'label'=>xe_trans($categoryItem->word)
                ];
            });
        });
        return $convertedCategoryItems;


    }

    private function convertCategoryItemArray($categoryItems)
    {
        $items = [];

        foreach ($categoryItems as $categoryItem) {
            $items[] = [
                'value' => $categoryItem->id,
                'text' => xe_trans($categoryItem->word),
            ];
        }

        return $items;
    }

    public function createProductCategory($productId, Request $request)
    {
        $newCategory = $request->get('newCategory', '');

        if ($newCategory == '') {
            return;
        }

        $categoryItems = explode(',', $newCategory);

        $this->handler->store($productId, $categoryItems);
    }

    public function removeProductCategory($productId)
    {
        $this->handler->remove($productId);
    }

    public function updateProductCategory($productId, Request $request)
    {
        $this->removeProductCategory($productId);
        $this->createProductCategory($productId, $request);
    }

    public function getProductCategory($productId)
    {
        return $this->handler->getIds($productId);
    }
}
