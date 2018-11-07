<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\DefaultWidget;

use View;
use Xpressengine\Plugins\XeroCommerce\Handlers\XeroCommerceImageHandler;
use Xpressengine\Widget\AbstractWidget;

class DefaultWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/DefaultWidget/views';

    public function render()
    {
        /** @var XeroCommerceImageHandler $imageHandler */
        $imageHandler = app('xero_commerce.imageHandler');

        $widgetConfig = $this->setting();

        $imageIds = $widgetConfig['images'];
        $images = [];

        foreach ($imageIds as $imageId) {
            $images[] = $imageHandler->getImageUrlByFileId($imageId);
        }

        return $this->renderSkin([
            'images' => $images
        ]);
    }

    public function renderSetting(array $args = [])
    {
        return View::make(sprintf('%s/setting', static::$path), [
            'args' => $args
        ]);
    }
}
