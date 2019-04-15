<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\LabelProductWidget;

use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
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




        if (is_array($widgetConfig['product_id']) === true) {
            $productIds = $widgetConfig['product_id'];
        } else {
            $productIds = explode(',', $widgetConfig['product_id']);
        }


        $products = Product::find($productIds);
        $categorizedProducts = [];
        $products->each(function(Product $product)use(&$categorizedProducts){

            $product->category->each(function(CategoryItem $categoryItem)use($product, &$categorizedProducts){
                $categorizedProducts[$categoryItem->id][]=$product;
            });
        });
        $categories = CategoryItem::whereIn('id', array_keys($categorizedProducts) )->get();
        return $this->renderSkin([
            'widgetConfig' => $widgetConfig,
            'categories' => $categories,
            'products' => $categorizedProducts
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
