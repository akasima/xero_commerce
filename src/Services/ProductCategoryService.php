<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Category\Models\Category;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class ProductCategoryService
{
    /** @var ProductCategoryHandler $handler  */
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

        $newCategory = substr($newCategory, 0, strlen($newCategory)-1);

        $categoryItems = explode(',', $newCategory);

        $this->handler->store($productId, $categoryItems);
    }
}
