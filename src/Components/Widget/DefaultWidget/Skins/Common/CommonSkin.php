<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\DefaultWidget\Skins\Common;

use View;
use Xpressengine\Skin\GenericSkin;

class CommonSkin extends GenericSkin
{
    protected static $path = 'xero_commerce/src/Components/Widget/DefaultWidget/Skins/Common';

    public function renderSetting(array $config = [])
    {
        return View::make(sprintf('%s/views/setting', static::$path), []);
    }
}
