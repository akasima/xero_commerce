<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\SlideWidget;

use View;
use Xpressengine\Widget\AbstractWidget;

class SlideWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/SlideWidget/views';

    public function render()
    {
        return $this->renderSkin([]);
    }

    public function renderSetting(array $args = [])
    {
        return View::make(sprintf('%s/setting', static::$path), [
            'args' => $args
        ]);
    }
}
