<?php

namespace Xpressengine\Plugins\XeroStore\Components\Modules;

use Route;
use Xpressengine\Menu\AbstractModule;

class XeroStoreCartModule extends AbstractModule
{
    public static function boot()
    {
        Route::instance(XeroStoreCartModule::getId(), function () {
            Route::get('/', ['as' => 'xero_store.cart.index', 'uses' => 'CartController@index']);
        }, ['namespace' => 'Xpressengine\\Plugins\\XeroStore\\Controllers']);
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