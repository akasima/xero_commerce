<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\LabelProductWidget;

use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;
use Xpressengine\Plugins\XeroCommerce\Services\ProductCategoryService;
use Xpressengine\Widget\AbstractWidget;
use View;

class LabelProductWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/LabelProductWidget/views';

    /** @var LabelHandler $labelHandler */
    protected $labelHandler;

    public function __construct(array $config = null)
    {
        $this->labelHandler = app('xero_commerce.labelHandler');

        parent::__construct($config);
    }

    public function render()
    {
        $widgetConfig = $this->setting();

        $labelId = $widgetConfig['label_id'];
        $label = $this->labelHandler->getLabel($labelId);

        if (is_array($widgetConfig['category_item_id']) === true) {
            $categoryIds = $widgetConfig['category_item_id'];
        } else {
            $categoryIds = explode(',', $widgetConfig['category_item_id']);
        }
        $categories = CategoryItem::whereIn('id', $categoryIds)->get();

        if (is_array($widgetConfig['product_id']) === true) {
            $productIds = $widgetConfig['product_id'];
        } else {
            $productIds = explode(',', $widgetConfig['product_id']);
        }

        $productCategories = ProductCategory::whereIn('category_id', $categoryIds)->get();

        $products = [];
        foreach ($productCategories as $category) {
            foreach ($category->product as $product) {
                if (in_array($product->id, $productIds) == true) {
                    $products[$category->category_id][] = $product;
                }
            }
        }

        return $this->renderSkin([
            'widgetConfig' => $widgetConfig,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function renderSetting(array $args = [])
    {
        $labels = $this->labelHandler->getLabels();
        $productCategoryService = new ProductCategoryService();
        $categoryItems = $productCategoryService->getCategoryItems();

        return View::make(sprintf('%s/setting', static::$path), [
            'args' => $args,
            'labels' => $labels,
            'categoryItems' => $categoryItems
        ]);
    }
}
