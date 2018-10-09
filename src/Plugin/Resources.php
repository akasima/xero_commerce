<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use App\Facades\XeInterception;
use XeRegister;
use Route;
use Xpressengine\Plugins\CkEditor\Editors\CkEditor;
use Xpressengine\Plugins\XeroCommerce\Controllers\Settings\ProductController;
use Xpressengine\Plugins\XeroCommerce\Handlers\BadgeHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Mark;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;
use Xpressengine\Routing\InstanceRoute;
use Xpressengine\User\Models\User;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\Test\TestHandler;

class Resources
{
    /**
     * @return void
     */
    public static function setXeroCommercePrefixRoute()
    {
        config(['xe.routing' => array_merge(
            config('xe.routing'),
            ['xero_commerce' => Plugin::XeroCommercePrefix]
        )]);
    }

    /**
     * @return bool
     */
    public static function isUsedXeroCommercePrefix()
    {
        if (InstanceRoute::where('url', Plugin::XeroCommercePrefix)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public static function registerRoute()
    {
        Route::settings('xero_commerce', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers\\Settings'
            ], function () {
                //상품관리
                Route::group(['prefix' => 'products'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.product.index',
                        'uses' => 'ProductController@index',
                        'settings_menu' => 'xero_commerce.product.list']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.product.create',
                        'uses' => 'ProductController@create', 'settings_menu' => 'xero_commerce.product.create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.product.store',
                        'uses' => 'ProductController@store']);
                    Route::get('/{productId}', ['as' => 'xero_commerce::setting.product.show',
                        'uses' => 'ProductController@show']);
                    Route::get('/{productId}/edit', ['as' => 'xero_commerce::setting.product.edit',
                        'uses' => 'ProductController@edit']);
                    Route::post('/{productId}/update', ['as' => 'xero_commerce::setting.product.update',
                        'uses' => 'ProductController@update']);
                    Route::post('/{productId}/remove', ['as' => 'xero_commerce::setting.product.remove',
                        'uses' => 'ProductController@remove']);

                    Route::get('/category/child', ['as' => 'xero_commerce:setting.product.category.getChild',
                        'uses' => 'ProductController@getChildCategory']);
                });

                //분류 관리
                Route::get('/category', ['as' => 'xero_commerce::setting.category.index',
                    'uses' => 'CategoryController@index',
                    'settings_menu' => 'xero_commerce.product.category']);

                //라벨 관리
                Route::group(['prefix' => 'label'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.label.index',
                        'uses' => 'LabelController@index',
                        'settings_menu' => 'xero_commerce.product.label']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.label.create',
                        'uses' => 'LabelController@create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.label.store',
                        'uses' => 'LabelController@store']);
                    Route::get('/edit/{id}', ['as' => 'xero_commerce::setting.label.edit',
                        'uses' => 'LabalController@edit']);
                    Route::post('/remove/{id}', ['as' => 'xero_commerce::setting.label.remove',
                        'uses' => 'LabelController@remove']);
                });

                //배지 관리
                Route::group(['prefix' => 'badge'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.badge.index',
                        'uses' => 'BadgeController@index',
                        'settings_menu' => 'xero_commerce.product.badge']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.badge.create',
                        'uses' => 'BadgeController@create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.badge.store',
                        'uses' => 'BadgeController@store']);
                    Route::get('/edit/{id}', ['as' => 'xero_commerce::setting.badge.edit',
                        'uses' => 'BadgeController@edit']);
                    Route::post('/remove/{id}', ['as' => 'xero_commerce::setting.badge.remove',
                        'uses' => 'BadgeController@remove']);
                });

                //주문 관리
                Route::group(['prefix' => 'order'], function () {
                    Route::get('/', [
                        'as' => 'xero_commerce::setting.order.index',
                        'uses' => 'OrderController@dash',
                        'settings_menu' => 'xero_commerce.order.index'
                    ]);
                    Route::get('/delivery', [
                        'as' => 'xero_commerce::setting.order.delivery',
                        'uses' => 'OrderController@delivery',
                        'settings_menu' => 'xero_commerce.order.delivery'
                    ]);
                    Route::post('/delivery',[
                        'as' => 'xero_commerce::process.order.delivery',
                        'uses'=>'OrderController@processDelivery'
                    ]);
                    Route::post('/delivery/complete', [
                        'as' => 'xero_commerce::complete.order.delivery',
                        'uses' => 'OrderController@completeDelivery'
                    ]);
                });

                //쇼핑몰 설정
                Route::group(['prefix' => 'configure'], function () {
                    //쇼핑몰 환경 설정
                    Route::get('/create', ['as' => 'xero_commerce::setting.config.create',
                        'uses' => 'ShopConfigController@create',
                        'settings_menu' => 'xero_commerce.config.shopInfo']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.config.store',
                        'uses' => 'ShopConfigController@store']);
                    Route::get('/setSkin', ['as' => 'xero_commerce::setting.config.skin',
                        'uses' => 'ShopConfigController@setSkin',
                        'settings_menu' => 'xero_commerce.config.setSkin']);
                    Route::get('/editTheme', ['as' => 'xero_commerce:setting.config.editTheme',
                        'uses' => 'ShopConfigController@editTheme',
                        'settings_menu' => 'xero_commerce.config.setTheme']);
                    Route::post('/updateTheme', ['as' => 'xero_commerce:setting.config.updateTheme',
                        'uses' => 'ShopConfigController@updateTheme']);

                    //입점몰 관리
                    Route::get('/shop', ['as' => 'xero_commerce::setting.config.shop.index',
                        'uses' => 'ShopController@index', 'settings_menu' => 'xero_commerce.config.storeInfo']);
                    Route::get('/shop/create', ['as' => 'xero_commerce::setting.config.shop.create',
                        'uses' => 'ShopController@create']);
                    Route::post('/shop/store', ['as' => 'xero_commerce::setting.config.shop.store',
                        'uses' => 'ShopController@store']);
                    Route::get('/shop/{shopId}', ['as' => 'xero_commerce::setting.config.shop.show',
                        'uses' => 'ShopController@show']);
                    Route::post('/shop/remove/{shopId}', ['as' => 'xero_commerce::setting.config.shop.remove',
                        'uses' => 'ShopController@remove']);
                    Route::get('/shop/edit/{shopId}', ['as' => 'xero_commerce::setting.config.shop.edit',
                        'uses' => 'ShopController@edit']);
                    Route::post('/shop/update/{shopId}', ['as' => 'xero_commerce::setting.config.shop.update',
                        'uses' => 'ShopController@update']);
                });
            });
        });

        //결제모듈 설정
        Route::namespace('Xpressengine\XePlugin\XeroPay')
            ->prefix('payment')
            ->group(function () {
                Route::post('/form', [
                    'as' => 'xero_pay::formList',
                    'uses' => 'Controller@formList'
                ]);
                Route::post('/callback', [
                    'as' => 'xero_pay::callback',
                    'uses' => 'Controller@callback'
                ]);
            });

        ProductSlugService::setReserved([
            'index', 'create', 'edit', 'update', 'store', 'show', 'remove', 'slug', 'hasSlug', 'cart', 'order', Plugin::XeroCommercePrefix
        ]);
    }

    /**
     * @return void
     */
    public static function bindClasses()
    {
        $app = app();

        $app->singleton(ShopHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ShopHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ShopHandler::class, 'xero_commerce.shopHandler');

        $app->singleton(ProductHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductHandler::class, 'xero_commerce.productHandler');

        $app->singleton(ProductOptionItemHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductOptionItemHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductOptionItemHandler::class, 'xero_commerce.productOptionItemHandler');

        $app->singleton(ProductCategoryHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductCategoryHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductCategoryHandler::class, 'xero_commerce.productCategoryHandler');

        $app->singleton(LabelHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(LabelHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(LabelHandler::class, 'xero_commerce.labelHandler');

        $app->singleton(BadgeHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(BadgeHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(BadgeHandler::class, 'xero_commerce.badgeHandler');

        $app->singleton(OrderHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(OrderHandler::class);

            $instance = new $proxyHandler(new Order());

            return $instance;
        });
        $app->alias(OrderHandler::class, 'xero_commerce.orderHandler');


        $app->singleton(CartHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(CartHandler::class);

            $instance = new $proxyHandler(User::first());

            return $instance;
        });
        $app->alias(CartHandler::class, 'xero_commerce.cartHandler');


        $app->singleton(PaymentHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(TestHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(PaymentHandler::class, 'xero_pay::paymentHandler');

        $app->when(ProductController::class)
            ->needs(SellUnit::class)
            ->give(ProductOptionItem::class);

        $app->when(ProductController::class)
            ->needs(SellType::class)
            ->give(Product::class);
    }

    /**
     * @return void
     */
    public static function setConfig()
    {
        \XeEditor::setInstance(Plugin::getId(), CkEditor::getId());
        \XeEditor::setConfig(Plugin::getId(), ['uploadActive' => false]);

        $category = \XeCategory::create([
            'name' => '상품 분류'
        ]);

        $config = \XeConfig::get(Plugin::getId());
        if ($config === null) {
            \XeConfig::add(Plugin::getId(), [
                'categoryId' => $category->id,
            ]);
        }

        self::storeDefaultMarks();
    }

    /**
     * @return void
     */
    public static function storeDefaultMarks()
    {
        $labels[] = ['name' => '히트', 'eng_name' => 'hit'];
        $labels[] = ['name' => '추천', 'eng_name' => 'recommend'];
        $labels[] = ['name' => '신규', 'eng_name' => 'new'];
        $labels[] = ['name' => '인기', 'eng_name' => 'popular'];
        $labels[] = ['name' => '할인', 'eng_name' => 'sale'];

        foreach ($labels as $label) {
            $newLabel = new Label();
            $newLabel->name = $label['name'];
            $newLabel->eng_name = $label['eng_name'];

            $newLabel->save();
        }

        $badges[] = ['name' => '세일', 'eng_name' => 'sale'];
        $badges[] = ['name' => '히트', 'eng_name' => 'hit'];

        foreach ($badges as $badge) {
            $newBadge = new Badge();
            $newBadge->name = $badge['name'];
            $newBadge->eng_name = $badge['eng_name'];

            $newBadge->save();
        }
    }

    /**
     * @return void
     */
    public static function storeDefaultShop()
    {
        $userId = \Auth::user()->getId();

        if (Shop::where('shop_name', Shop::BASIC_SHOP_NAME)->first() == null) {
            $args['user_id'] = $userId;
            $args['shop_type'] = Shop::TYPE_STORE;
            $args['shop_name'] = Shop::BASIC_SHOP_NAME;

            $storeHandler = new ShopHandler();
            $storeHandler->store($args);
        }
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
                'xero_commerce' => [
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
            'xero_commerce.product' => [
                'title' => '상품관리',
                'display' => true,
                'description' => '',
                'ordering' => 10001
            ],
            'xero_commerce.product.list' => [
                'title' => '전체 상품목록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'xero_commerce.product.create' => [
                'title' => '상품 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
            'xero_commerce.product.category' => [
                'title' => '분류 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100013
            ],
            'xero_commerce.product.label' => [
                'title' => '라벨 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100014
            ],
            'xero_commerce.product.badge' => [
                'title' => '배지 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100015
            ]
        ];
    }

    /**
     * @return array
     */
    private static function menuOrder()
    {
        return [
            'xero_commerce.order' => [
                'title' => '주문',
                'display' => true,
                'description' => '',
                'ordering' => 10002
            ],
            'xero_commerce.order.index' => [
                'title' => '전체 주문내역',
                'display' => true,
                'description' => '',
                'ordering' => 100021
            ],
            'xero_commerce.order.delivery' => [
                'title' => '주문 배송처리',
                'display' => true,
                'description' => '',
                'ordering' => 100022
            ],
        ];
    }

    /**
     * @return array
     */
    private static function menuConfigure()
    {
        return [
            'xero_commerce.config' => [
                'title' => '환경설정',
                'display' => true,
                'description' => '',
                'ordering' => 10003
            ],
            'xero_commerce.config.shopInfo' => [
                'title' => '쇼핑몰 정보 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100031
            ],
            'xero_commerce.config.setSkin' => [
                'title' => '스킨 설정',
                'display' => true,
                'description' => '',
                'ordering' => 100032
            ],
            'xero_commerce.config.setTheme' => [
                'title' => '테마 설정',
                'display' => true,
                'description' => '',
                'ordering' => 100033
            ],
            'xero_commerce.config.storeInfo' => [
                'title' => '입점몰 정보',
                'display' => true,
                'description' => '',
                'ordering' => 100034
            ],
        ];
    }
}
