<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\ProductListWidget\Skins\Common;

use Xpressengine\Skin\GenericSkin;
use View;

class CommonSkin extends GenericSkin
{
    protected static $path = 'xero_commerce/src/Components/Widget/ProductListWidget/Skins/Common';

    public function renderSetting(array $config = [])
    {
        return View::make(sprintf('%s/views/setting', static::$path), []);
    }
}
