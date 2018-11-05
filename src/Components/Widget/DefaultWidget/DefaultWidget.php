<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\DefaultWidget;

use View;
use Xpressengine\Widget\AbstractWidget;

class DefaultWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/DefaultWidget/views';

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
