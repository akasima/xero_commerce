<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\LabelProductWidget;

use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
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

        if (is_array($widgetConfig['category_item_id']['item']) === true) {
            $categoryIds = $widgetConfig['category_item_id']['item'];
        } else {
            $categoryIds = [$widgetConfig['category_item_id']['item']];
        }
        $categories = CategoryItem::whereIn('id', $categoryIds)->get();

        if (is_array($widgetConfig['product_id']) === true) {
            $productIds = $widgetConfig['product_id'];
        } else {
            $productIds = [$widgetConfig['product_id']];
        }
        $products = Product::whereIn('id', $productIds)->get();

        return $this->renderSkin([
            'widgetConfig' => $widgetConfig,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function renderSetting(array $args = [])
    {
        $labels = $this->labelHandler->getLabels();

        return View::make(sprintf('%s/setting', static::$path), [
            'args' => $args,
            'labels' => $labels
        ]);
    }
}
