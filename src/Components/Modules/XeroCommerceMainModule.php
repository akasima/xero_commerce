<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use XeConfig;
use Xpressengine\Menu\AbstractModule;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceMainModule extends AbstractModule
{
    protected $moduleListConfigKey;

    public function __construct()
    {
        $this->moduleListConfigKey = sprintf('%s.%s', Plugin::getId(), 'mainModuleList');
    }

    public static function boot()
    {
        Route::instance(XeroCommerceMainModule::getId(), function () {
            Route::get('/', ['as' => 'xero_commerce::main.module', 'uses' => 'MainModuleController@index']);
        }, ['namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers\\MainModule']);
    }

    public function createMenuForm()
    {
        return '';
    }

    public function storeMenu($instanceId, $menuTypeParams, $itemParams)
    {
        $moduleListConfig = XeConfig::get($this->moduleListConfigKey);
        if ($moduleListConfig == null) {
            $moduleListConfig = XeConfig::add($this->moduleListConfigKey, []);
        }

        $moduleList = $moduleListConfig->get('moduleList', []);

        array_push($moduleList, $instanceId);

        $moduleListConfig->set('moduleList', $moduleList);

        XeConfig::modify($moduleListConfig);

        return '';
    }

    public function editMenuForm($instanceId)
    {
        return '';
    }

    public function updateMenu($instanceId, $menuTypeParams, $itemParams)
    {
        return '';
    }

    public function summary($instanceId)
    {
        return '';
    }

    public function deleteMenu($instanceId)
    {
        $moduleListConfig = XeConfig::get($this->moduleListConfigKey);
        if ($moduleListConfig == null) {
            return '';
        }

        $moduleList = $moduleListConfig->get('moduleList', []);

        if (($key = array_search($instanceId, $moduleList)) !== false) {
            unset($moduleList[$key]);
        }

        $moduleListConfig->set('moduleList', $moduleList);

        XeConfig::modify($moduleListConfig);

        return '';
    }

    public function getTypeItem($id)
    {
        return '';
    }
}
