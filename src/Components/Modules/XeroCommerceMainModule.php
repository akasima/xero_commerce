<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use Xpressengine\Menu\AbstractModule;

class XeroCommerceMainModule extends AbstractModule
{
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
        return '';
    }

    public function getTypeItem($id)
    {
        return '';
    }
}
