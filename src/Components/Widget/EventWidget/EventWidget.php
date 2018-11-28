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

        $products['product_id_1'] = $productHandler->getProduct($widgetConfig['product_id_1']);
        $products['product_id_2'] = $productHandler->getProduct($widgetConfig['product_id_2']);
        $products['product_id_3'] = $productHandler->getProduct($widgetConfig['product_id_3']);
        $products['product_id_4'] = $productHandler->getProduct($widgetConfig['product_id_4']);
        $products['product_id_5'] = $productHandler->getProduct($widgetConfig['product_id_5']);

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
