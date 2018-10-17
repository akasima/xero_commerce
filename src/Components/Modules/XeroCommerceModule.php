<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use XeConfig;
use View;
use Xpressengine\Category\Models\Category;
use Xpressengine\Menu\AbstractModule;
use Xpressengine\Plugins\XeroCommerce\Middleware\AgreementMiddleware;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceModule extends AbstractModule
{
    public static function boot()
    {
        Route::group([
            'prefix' => Plugin::XeroCommercePrefix,
            'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers',
            'middleware' => ['web']
        ], function () {
            Route::get('/cart', [
                'uses' => 'CartController@index',
                'as' => 'xero_commerce::cart.index'
            ]);
            Route::get('/cart/draw/{cart}', [
                'uses' => 'CartController@draw',
                'as' => 'xero_commerce::cart.draw'
            ]);
            Route::get('/cart/change/{cart}', [
                'uses' => 'CartController@change',
                'as' => 'xero_commerce::cart.change'
            ]);
            Route::get('/cart/list', [
                'uses' => 'CartController@list',
                'as' => 'xero_commerce::cart.list'
            ]);
            Route::get('/cart/summary', [
                'uses' => 'CartController@summary',
                'as' => 'xero_commerce::cart.summary'
            ]);
            Route::post('/order', [
                'uses' => 'OrderController@register',
                'as' => 'xero_commerce::order.register'
            ]);
            Route::get('/order/register', [
                'uses' => 'OrderController@registerAgain',
                'as' => 'xero_commerce::order.register.again'
            ])->middleware(AgreementMiddleware::class);
            Route::get('/order', [
                'uses' => 'OrderController@index',
                'as' => 'xero_commerce::order.index'
            ]);
            Route::get('/order/detail/{order}', [
                'uses' => 'OrderController@detail',
                'as' => 'xero_commerce::order.detail'
            ]);
            Route::get('/order/list', [
                'uses' => 'OrderController@list',
                'as' => 'xero_commerce::order.list'
            ]);
            Route::post('/order/list/{page}', [
                'uses' => 'OrderController@listJson',
                'as' => 'xero_commerce::order.page'
            ]);
            Route::post('/order/pay/{order}', [
                'uses'=>'OrderController@pay',
                'as'=>'xero_commerce::order.pay'
            ]);
            Route::post('/order/success/{order}', [
                'uses'=>'OrderController@success',
                'as'=>'xero_commerce::order.success'
            ]);
            Route::get('/order/fail/{order}', [
                'uses' => 'OrderController@fail',
                'as' => 'xero_commerce::order.fail'
            ]);
            Route::get('/order/service/{as}/{order}/{orderItem}', [
                'uses' => 'OrderController@afterService',
                'as' => 'xero_commerce::order.as'
            ]);

            Route::post('/order/service/{type}/{orderItem}', [
                'uses' => 'OrderController@asRegister',
                'as' => 'xero_commerce::order.as.register'
            ]);

            Route::post('/product/cart/{product}', [
                'uses' => 'ProductController@cartAdd',
                'as' => 'xero_commerce::product.cart'
            ]);

            Route::get('/test/{product}', 'CartController@test');

            Route::get('/{strSlug}', ['as' => 'xero_commerce::product.show', 'uses' => 'ProductController@show']);

            Route::get('/agreement/contacts', [
                'uses' => 'AgreementController@contacts',
                'as' => 'xero_commerce::agreement.contacts'
            ]);
            Route::post('/agreement/contacts', [
                'uses' => 'AgreementController@saveContacts',
                'as' => 'xero_commerce::agreement.contacts.save'
            ]);
        });

        Route::instance(XeroCommerceModule::getId(), function () {
            Route::get('/', ['as' => 'xero_commerce::product.index', 'uses' => 'ProductController@index']);
        }, ['namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers']);
    }

    public function createMenuForm()
    {
        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();
        $labels = Label::get();
        $plugin = Plugin::class;

        return View::make('xero_commerce::views/setting/module/create', [
            'categoryItems' => $categoryItems,
            'plugin' => $plugin,
            'labels' => $labels
        ])->render();
    }

    public function storeMenu($instanceId, $menuTypeParams, $itemParams)
    {
        $configValue['categoryItemId'] = $menuTypeParams['categoryItemId'];
        $configValue['categoryItemDepth'] = $menuTypeParams['categoryItemDepth'];

        //menuTypeParams['label'][0]은 null이 들어있음
        if (isset($menuTypeParams['labels'][1])) {
            array_shift($menuTypeParams['labels']);
            $configValue['labels'] = $menuTypeParams['labels'];
        }

        XeConfig::add(sprintf('%s.%s', Plugin::getId(), $instanceId), $configValue);

        return '';
    }

    public function editMenuForm($instanceId)
    {
        //TODO 수정 기존 데이터 전달 필요
        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        $labels = Label::get();
        $moduleLabels = XeConfig::get(sprintf('%s.%s', Plugin::getId(), $instanceId))['labels'];
        if ($moduleLabels === null) {
            $moduleLabels = [];
        }

        $plugin = Plugin::class;

        return View::make('xero_commerce::views/setting/module/edit', [
            'categoryItems' => $categoryItems,
            'plugin' => $plugin,
            'labels' => $labels,
            'moduleLabels' => $moduleLabels
        ])->render();
    }

    public function updateMenu($instanceId, $menuTypeParams, $itemParams)
    {
        $config = XeConfig::get(sprintf('%s.%s', Plugin::getId(), $instanceId));

        $config['categoryItemId'] = $menuTypeParams['categoryItemId'];
        $config['categoryItemDepth'] = $menuTypeParams['categoryItemDepth'];

        if (isset($menuTypeParams['labels'][1])) {
            array_shift($menuTypeParams['labels']);
            $config['labels'] = $menuTypeParams['labels'];
        }

        XeConfig::modify($config);

        return '';
    }

    public function summary($instanceId)
    {
        return '';
    }

    public function deleteMenu($instanceId)
    {
        XeConfig::remove(XeConfig::get(sprintf('%s.%s', Plugin::getId(), $instanceId)));

        return '';
    }

    public function getTypeItem($id)
    {
        return '';
    }
}
