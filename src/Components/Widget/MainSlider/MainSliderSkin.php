<?php
namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\MainSlider;

use Xpressengine\Skin\GenericSkin;
use View;

use Xpressengine\Plugins\Banner\BannerWidgetSkin;

class MainSliderSkin extends BannerWidgetSkin
{
    /**
     * @var string
     */
    protected static $path = 'xero_commerce/src/Components/Widget/MainSlider';

    public static function getBannerInfo($key = null)
    {
        if ($key) {
            $key = '.' . $key;
        }
        return static::info('banner'.$key);
    }

    public function renderBannerSetting()
    {
        return '';
    }
}
