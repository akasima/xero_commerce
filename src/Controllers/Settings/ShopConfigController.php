<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Sections\SkinSection;
use XeConfig;
use XePresenter;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceModule;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Theme\ThemeHandler;

class ShopConfigController
{
    public function create()
    {
        $config = XeConfig::getOrNew(Plugin::getId());

        return XePresenter::make('xero_commerce::views.setting.config.config', compact('config'));
    }

    public function store(Request $request)
    {
        $config = XeConfig::getOrNew(Plugin::getId());

        $inputs = $request->except(['_token']);

        foreach ($inputs as $key => $input) {
            $config->set($key, $input);
        }

        XeConfig::modify($config);

        return redirect()->route('xero_commerce::setting.config.create')
            ->with('alert', ['type' => 'success', 'message' => '저장 완료']);
    }

    public function setSkin()
    {
        $skinSection = new SkinSection(XeroCommerceModule::getId());

        return XePresenter::make('xero_commerce::views.setting.config.skin', compact('skinSection'));
    }

    public function editTheme()
    {
        $mainMenuId = \XeConfig::get(Plugin::getId())->get('mainMenuId', '');

        if ($mainMenuId == '') {
            return;
        }

        $menuTheme = \XeMenu::getMenuTheme(\XeMenu::menus()->findWith($mainMenuId));

        $selectedTheme['desktop'] = $menuTheme['desktopTheme'];
        $selectedTheme['mobile'] = $menuTheme['mobileTheme'];

        return \XePresenter::make('xero_commerce::views.setting.config.theme', compact('selectedTheme'));
    }

    public function updateTheme(Request $request)
    {
        $mainMenuId = \XeConfig::get(Plugin::getId())->get('mainMenuId', '');

        if ($mainMenuId == '') {
            return;
        }

        $desktopTheme = $request->get('theme_desktop');
        $mobileTheme = $request->get('theme_mobile');

        $menu = \XeMenu::menus()->findWith($mainMenuId);

        \XeMenu::updateMenuTheme($menu, $desktopTheme, $mobileTheme);

        return \Redirect::back()->with('alert', ['type' => 'success', 'message' => xe_trans('xe::saved')]);
    }
}
