<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use Xpressengine\Menu\AbstractModule;

class XeroCommerceModule extends AbstractModule
{
    public static function boot()
    {
        Route::instance(XeroCommerceModule::getId(), function () {
            Route::get('/', ['as' => 'xero_store.product.index', 'uses' => 'ProductController@index']);
        }, ['namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers']);
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
