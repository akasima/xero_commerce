<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceBasicController extends Controller
{
    public function __construct()
    {
        $themeHandler = \XePresenter::getThemeHandler();

        $mainMenuId = \XeConfig::get(Plugin::getId())->get('mainMenuId', '');

        if ($mainMenuId == '') {
            return;
        }

        $menuTheme = \XeMenu::getMenuTheme(\XeMenu::menus()->findWith($mainMenuId));
        $themeHandler->selectTheme($menuTheme['desktopTheme']);

        \XePresenter::setSkinTargetId(\Xpressengine\Plugins\XeroCommerce\Plugin::getId());
    }
}
