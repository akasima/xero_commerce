<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use Xpressengine\Menu\AbstractModule;

class XeroCommerceModule extends AbstractModule
{
    public static function boot()
    {
        Route::instance(XeroCommerceModule::getId(), function () {
            Route::get('/', ['as' => 'xero_commerce.product.index', 'uses' => 'ProductController@index']);

            Route::get('/cart', [
                'uses' => 'CartController@index',
                'as' => 'xero_commerce::cart.index'
            ]);
            Route::get('/cart/draw/{cart}', [
                'uses' => 'CartController@draw',
                'as' => 'xero_commerce::cart.draw'
            ]);
            Route::post('/order', [
                'uses' => 'OrderController@register',
                'as' => 'xero_commerce::order.register'
            ]);
            Route::get('/order/{order}', [
                'uses' => 'OrderController@registerAgain',
                'as' => 'xero_commerce::order.register.again'
            ]);
            Route::get('/order', [
                'uses' => 'OrderController@index',
                'as' => 'xero_commerce::order.index'
            ]);
            Route::get('/order/fail/{order}', [
                'uses' => 'OrderController@fail',
                'as' => 'xero_commerce::order.fail'
            ]);
            Route::get('/test/{product}', 'CartController@test');
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
