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
        $categories = CategoryItem::whereIn('id', [$widgetConfig['category_item_id']])->get();
        $products = Product::whereIn('id', [$widgetConfig['product_id']])->get();

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
