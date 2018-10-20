<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use Xpressengine\Menu\AbstractModule;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceMainModule extends AbstractModule
{
    public static function boot()
    {
        Route::group([
            'prefix' => Plugin::XeroCommercePrefix,
            'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers\\MainModule',
            'middleware' => ['web']
        ], function () {
            Route::get('/', ['as' => 'xero_commerce::main.index', 'uses' => 'MainModuleController@index']);
        });
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
