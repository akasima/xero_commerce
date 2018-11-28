<?php
namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\RecommendSlider;

use Xpressengine\Skin\GenericSkin;
use View;

use Xpressengine\Plugins\Banner\BannerWidgetSkin;

class RecommendSliderSkin extends BannerWidgetSkin
{
    /**
     * @var string
     */
    protected static $path = 'xero_commerce/src/Components/Widget/RecommendSlider';

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
