<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\DefaultWidget;

use View;
use Xpressengine\Storage\File;
use Xpressengine\Widget\AbstractWidget;

class DefaultWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/DefaultWidget/views';

    public function render()
    {
        $widgetConfig = $this->setting();

        $media = app('xe.media');
        $imageIds = $widgetConfig['images'];
        $images = [];

        foreach ($imageIds as $imageId) {
            $file = File::where('id', $imageId)->get()->first();

            $mediaFile = $media->make($file);

            $images[] = $mediaFile->url();
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
