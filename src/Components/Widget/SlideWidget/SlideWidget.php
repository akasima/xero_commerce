<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\SlideWidget;

use View;
use Xpressengine\Plugins\XeroCommerce\Handlers\XeroCommerceImageHandler;
use Xpressengine\Storage\File;
use Xpressengine\Widget\AbstractWidget;

class SlideWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/SlideWidget/views';

    public function render()
    {
        /** @var XeroCommerceImageHandler $imageHandler */
        $imageHandler = app('xero_commerce.imageHandler');

        $widgetConfig = $this->setting();

        $imageIds = $widgetConfig['images'];
        $images = [];

        foreach ($imageIds['item'] as $imageId) {
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
