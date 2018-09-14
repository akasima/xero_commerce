<?php

namespace Xpressengine\Plugins\XeroStore\Controllers\Settings;

use XeConfig;
use XePresenter;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Plugin;

class ShopConfigController
{
    public function create()
    {
        $config = XeConfig::getOrNew(Plugin::getId());

        return XePresenter::make('xero_store::views.setting.config.create', compact('config'));
    }

    public function store(Request $request)
    {
        $config = XeConfig::getOrNew(Plugin::getId());

        $inputs = $request->except(['_token']);

        foreach ($inputs as $key => $input) {
            $config->set($key, $input);
        }

        XeConfig::modify($config);

        return redirect()->route('xero_store::setting.config.create')
            ->with('alert', ['type' => 'success', 'message' => '저장 완료']);
    }
}
