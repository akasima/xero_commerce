<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\LabelProductWidget;

use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Widget\AbstractWidget;
use View;

class LabelProductWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/LabelProductWidget/views';

    public function render()
    {
        $widgetConfig = $this->setting();
        $labelId = $widgetConfig['label_id'];



        return $this->renderSkin([
            'labelId' => $labelId
        ]);
    }

    public function renderSetting(array $args = [])
    {
        /** @var LabelHandler $labelHandler */
        $labelHandler = app('xero_commerce.labelHandler');

        $labels = $labelHandler->getLabels();

        return View::make(sprintf('%s/setting', static::$path), [
            'args' => $args,
            'labels' => $labels
        ]);
    }
}
