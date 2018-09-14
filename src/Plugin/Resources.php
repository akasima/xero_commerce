<?php

namespace Xpressengine\Plugins\XeroStore\Plugin;

use App\Facades\XeInterception;
use XeRegister;
use Route;
use Xpressengine\Plugins\XeroStore\Handlers\CartHandler;
use Xpressengine\Plugins\XeroStore\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroStore\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroStore\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroStore\Handlers\StoreHandler;
use Xpressengine\Plugins\XeroStore\Models\Store;
use Xpressengine\Plugins\XeroStore\Models\Order;
use Xpressengine\Plugins\XeroStore\Plugin;
use Xpressengine\User\Models\User;

class Resources
{
    /**
     * @return void
     */
    public static function registerRoute()
    {
        Route::settings('xero_store', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\XeroStore\\Controllers\\Settings'
            ], function () {
                //상품관리
                Route::group(['prefix' => 'product'], function () {
                    Route::get('/', ['as' => 'xero_store::setting.product.index', 'uses' => 'ProductController@index',
                        'settings_menu' => 'xero_store.product.list']);
                    Route::get('/create', ['as' => 'xero_store::setting.product.create',
                        'uses' => 'ProductController@create', 'settings_menu' => 'xero_store.product.create']);
                    Route::post('/store', ['as' => 'xero_store::setting.product.store',
                        'uses' => 'ProductController@store']);
                    Route::get('/{productId}', ['as' => 'xero_store::setting.product.show',
                        'uses' => 'ProductController@show']);
                });

                //분류 관리
                Route::get('/category', ['as' => 'xero_store::setting.category.index',
                    'uses' => 'CategoryController@index',
                    'settings_menu' => 'xero_store.product.category']);

                //주문 관리
                Route::group(['prefix' => 'order'], function () {
                    Route::get('/', [
                        'as' => 'xero_store::setting.order.index',
                        'uses' => 'OrderController@index',
                        'settings_menu' => 'xero_store.order.index'
                    ]);
                });
            });
        });
        Route::fixed('xero_store', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\XeroStore\\Controllers'
            ], function () {
                Route::get('/cart', 'CartController@index');
            });
        });
    }

    /**
     * @return void
     */
    public static function bindClasses()
    {
        $app = app();

        $app->singleton(StoreHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(StoreHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(StoreHandler::class, 'xero_store.storeHandler');

        $app->singleton(ProductHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductHandler::class, 'xero_store.productHandler');

        $app->singleton(ProductOptionItemHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductOptionItemHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductOptionItemHandler::class, 'xero_store.productOptionItemHandler');

        $app->singleton(OrderHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(OrderHandler::class);

            $instance = new $proxyHandler(new Order());

            return $instance;
        });
        $app->alias(OrderHandler::class, 'xero_store.orderHandler');


        $app->singleton(CartHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(CartHandler::class);

            $instance = new $proxyHandler(User::first());

            return $instance;
        });
        $app->alias(CartHandler::class, 'xero_store.cartHandler');
    }

    /**
     * @return void
     */
    public static function setConfig()
    {
        $category = \XeCategory::create([
            'name' => '상품 분류'
        ]);

        $config = \XeConfig::get(Plugin::getId());
        if ($config === null) {
            \XeConfig::add(Plugin::getId(), [
                'categoryId' => $category->id,
            ]);
        }
    }

    public static function storeDefaultStore()
    {
        $userId = \Auth::user()->getId();

        $newStore = new Store();
        $newStore['user_id'] = $userId;
        $newStore['store_type'] = Store::TYPE_STORE;
        $newStore['store_name'] = 'basic store';
        $newStore->save();
    }

    /**
     * 관리자에 메뉴 등록
     *
     * @return void
     */
    public static function registerSettingMenu()
    {
        $menus = array_merge(
            [
                'xero_store' => [
                    'title' => '쇼핑몰 관리',
                    'display' => true,
                    'description' => '',
                    'ordering' => 10000
                ],
            ],
            static::menuConfigure(),
            static::menuOrder(),
            static::menuProduct()
        );

        foreach ($menus as $id => $menu) {
            XeRegister::push('settings/menu', $id, $menu);
        }
    }

    /**
     * @return array
     */
    private static function menuProduct()
    {
        return [
            'xero_store.product' => [
                'title' => '상품관리',
                'display' => true,
                'description' => '',
                'ordering' => 10001
            ],
            'xero_store.product.list' => [
                'title' => '전체 상품목록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'xero_store.product.category' => [
                'title' => '분류 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
            'xero_store.product.create' => [
                'title' => '상품 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100013
            ],
        ];
    }

    /**
     * @return array
     */
    private static function menuOrder()
    {
        return [
            'xero_store.order' => [
                'title' => '주문',
                'display' => true,
                'description' => '',
                'ordering' => 10002
            ],
            'xero_store.order.index' => [
                'title' => '전체 주문내역',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
        ];
    }

    /**
     * @return array
     */
    private static function menuConfigure()
    {
        return [
            'xero_store.configure' => [
                'title' => '환경설정',
                'display' => true,
                'description' => '',
                'ordering' => 10003
            ],

            'xero_store.configure.shopInfo' => [
                'title' => '정보 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
        ];
    }
}
