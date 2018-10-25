<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\EventWidget;

use Xpressengine\Widget\AbstractWidget;
use View;

class EventWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/EventWidget/views';

    public function render()
    {
        $widgetConfig = $this->setting();

        $productHandler = app('xero_commerce.productHandler');

        $products['left'] = $productHandler->getProduct($widgetConfig['left_product_id']);
        $products['center_up'] = $productHandler->getProduct($widgetConfig['center_up_product_id']);
        $products['center_down'] = $productHandler->getProduct($widgetConfig['center_down_product_id']);
        $products['right'] = $productHandler->getProduct($widgetConfig['right_product_id']);

        return $this->renderSkin([
            'widgetConfig' => $widgetConfig,
            'products' => $products,
        ]);
    }

    public function renderSetting(array $args = [])
    {
        return View::make(sprintf('%s/setting', static::$path), [
            'args' => $args
        ]);
    }
}
