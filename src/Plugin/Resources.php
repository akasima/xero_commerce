<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use App\Facades\XeCategory;
use App\Facades\XeConfig;
use App\Facades\XeInterception;
use App\Facades\XeLang;
use App\Facades\XeMedia;
use App\Facades\XeStorage;
use Faker\Factory;
use Illuminate\Support\Facades\Auth;
use XeRegister;
use XeDB;
use XeMenu;
use Route;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Document\ConfigHandler;
use Xpressengine\Http\Request;
use Xpressengine\Menu\MenuHandler;
use Xpressengine\Permission\Grant;
use Xpressengine\Plugins\Banner\Widgets\Widget;
use Xpressengine\Plugins\CkEditor\Editors\CkEditor;
use Xpressengine\Plugins\XeroCommerce\Components\Modules\XeroCommerceModule;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\DefaultWidget\Skins\Common\CommonSkin as DefaultWidgetCommonSkin;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\MainSlider\MainSliderSkin;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\RecommendSlider\RecommendSliderSkin;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\SlideWidget\Skins\Common\CommonSkin as SlideWidgetCommonSkin;
use Xpressengine\Plugins\XeroCommerce\Controllers\Settings\ProductController;
use Xpressengine\Plugins\XeroCommerce\Handlers\BadgeHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\CommunicationHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\FeedbackHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCustomOptionHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\QnaHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\WishHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\XeroCommerceImageHandler;
use Xpressengine\Plugins\XeroCommerce\Middleware\AgreementMiddleware;
use Xpressengine\Plugins\XeroCommerce\Models\Agreement;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Carrier;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;
use Xpressengine\Plugins\XeroCommerce\Models\ProductLabel;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\ShopUser;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;
use Xpressengine\Routing\InstanceRoute;
use Xpressengine\Settings\SettingsHandler;
use Xpressengine\User\Models\User;
use Xpressengine\User\Rating;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;

class Resources
{

    public static function setCanUseXeroCommercePrefixRoute()
    {
        $routing = config('xe.routing');

        array_forget($routing, 'xero_commerce');

        config(['xe.routing' => $routing]);
    }

    /**
     * @return void
     */
    public static function setCanNotUseXeroCommercePrefixRoute()
    {
        config(['xe.routing' => array_merge(
            config('xe.routing'),
            ['xero_commerce' => Plugin::XERO_COMMERCE_URL_PREFIX]
        )]);
    }

    public static function setThumnailDimensionSetting()
    {
        config(['xe.media.thumbnail.dimensions' => array_merge(
            config('xe.media.thumbnail.dimensions'),
            [
                'T' => ['width' => 50, 'height' => 40],
                'B' => ['width' => 500, 'height' => 500]
            ]
        )]);
    }

    /**
     * @return bool
     */
    public static function isUsedXeroCommercePrefix()
    {
        if (InstanceRoute::where('url', Plugin::XERO_COMMERCE_URL_PREFIX)->count() > 0) {
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
        Route::group([
            'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers',
            //TODO config 변경
            'prefix' => Plugin::XERO_COMMERCE_URL_PREFIX,
            'middleware' => ['web']
        ], function () {

            Route::get('/wish', [
                'uses' => 'WishController@index',
                'as' => 'xero_commerce::wish.index'
            ]);

            Route::post('/wish/remove', [
                'uses' => 'WishController@remove',
                'as' => 'xero_commerce::wish.remove'
            ]);

            Route::post('/cart/wish', [
                'uses' => 'CartController@wishMany',
                'as' => 'xero_commerce::cart.wish'
            ]);

            Route::get('/cart', [
                'uses' => 'CartController@index',
                'as' => 'xero_commerce::cart.index'
            ]);
            Route::get('/cart/draw/{cart}', [
                'uses' => 'CartController@draw',
                'as' => 'xero_commerce::cart.draw'
            ]);
            Route::get('/cart/draw-list', [
                'uses' => 'CartController@drawList',
                'as' => 'xero_commerce::cart.drawList'
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


            Route::post('/product/cart/{product}', [
                'uses' => 'ProductController@cartAdd',
                'as' => 'xero_commerce::product.cart'
            ]);

            Route::get('/wish/toggle/{product}', [
                'uses' => 'ProductController@wishToggle',
                'as' => 'xero_commerce::product.wish.toggle'
            ]);

            Route::get('/feedback/product/{product}', [
                'uses' => 'ProductController@feedbackLoad',
                'as' => 'xero_commerce::product.feedback.get'
            ]);

            Route::post('/feedback/product/{product}', [
                'uses' => 'ProductController@feedbackAdd',
                'as' => 'xero_commerce::product.feedback.add'
            ]);

            Route::get('/qna/product/{product}', [
                'uses' => 'ProductController@qnaLoad',
                'as' => 'xero_commerce::product.qna.get'
            ]);

            Route::post('/qna/product/{product}', [
                'uses' => 'ProductController@qnaAdd',
                'as' => 'xero_commerce::product.qna.add'
            ]);

            Route::post('/qna/answer/{qna}', [
                'uses' => 'QnaController@answer',
                'as' => 'xero_commerce::qna.answer'
            ]);

            Route::get('/order', [
                'uses' => 'OrderController@index',
                'as' => 'xero_commerce::order.index'
            ])->middleware(['auth', AgreementMiddleware::class]);
            Route::post('/order', [
                'uses' => 'OrderController@register',
                'as' => 'xero_commerce::order.register'
            ]);
            Route::get('/order/register', [
                'uses' => 'OrderController@registerAgain',
                'as' => 'xero_commerce::order.register.again'
            ])->middleware(['auth', AgreementMiddleware::class]);
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
                'uses' => 'OrderController@pay',
                'as' => 'xero_commerce::order.pay'
            ]);

            Route::post('/order/cancel/{order}', [
                'uses' => 'OrderController@cancel',
                'as' => 'xero_commerce::order.cancel.register'
            ]);

            Route::post('/order/success/{order}', [
                'uses' => 'OrderController@success',
                'as' => 'xero_commerce::order.success'
            ]);
            Route::get('/order/fail/{order}', [
                'uses' => 'OrderController@fail',
                'as' => 'xero_commerce::order.fail'
            ]);

            Route::get('/order/complete/{order}', [
                'uses' => 'OrderController@complete',
                'as' => 'xero_commerce::order.complete'
            ]);
            Route::get('/order/service/cancel/{order}', [
                'uses' => 'OrderController@cancelService',
                'as' => 'xero_commerce::order.cancel'
            ]);

            Route::get('/order/service/{as}/{order}/{orderItem}', [
                'uses' => 'OrderController@afterService',
                'as' => 'xero_commerce::order.as'
            ]);

            Route::post('/order/service/{type}/{orderItem}', [
                'uses' => 'OrderController@asRegister',
                'as' => 'xero_commerce::order.as.register'
            ]);

            Route::get('/agreement/contacts', [
                'uses' => 'AgreementController@contacts',
                'as' => 'xero_commerce::agreement.contacts'
            ]);
            Route::post('/agreement/contacts', [
                'uses' => 'AgreementController@saveContacts',
                'as' => 'xero_commerce::agreement.contacts.save'
            ]);
            Route::post('/agreement/order/{order}', [
                'uses' => 'AgreementController@saveOrderAgree',
                'as' => 'xero_commerce::agreement.order.save'
            ]);
            Route::post('/agreement/cancel/order/{order}', [
                'uses' => 'AgreementController@cancelOrderAgree',
                'as' => 'xero_commerce::agreement.order.cancel'
            ]);

            Route::get('/no-shipment', [
                'as' => 'xero_commerce::no-shipment',
                'uses' => 'ShipmentController@index'
            ]);

            Route::post('/shipment/store', [
                'as' => 'xero_commerce::shipment.store',
                'uses' => 'ShipmentController@store'
            ]);

            Route::get('/{strSlug}', [
                'as' => 'xero_commerce::product.show',
                'uses' => 'ProductController@show'
            ]);
        });


        Route::settings('xero_commerce', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers\\Settings',
                'middleware' => ['web']
            ], function () {
                //상품관리
                Route::group(['prefix' => 'products'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.product.index',
                        'uses' => 'ProductController@index',
                        'settings_menu' => 'xero_commerce.product.list',
                        'permission' => 'xero_commerce']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.product.create',
                        'uses' => 'ProductController@create',
                        'settings_menu' => 'xero_commerce.product.create',
                        'permission' => 'xero_commerce']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.product.store',
                        'uses' => 'ProductController@store',
                        'permission' => 'xero_commerce']);
                    Route::get('/search', ['as' => 'xero_commerce:setting.product.search',
                        'uses' => 'ProductController@search',
                        'permission' => 'xero_commerce']);
                    Route::post('/{productId}/bundle/items', ['as' => 'xero_commerce::setting.product.bundle.items',
                        'uses' => 'ProductController@storeBundleItem',
                        'permission' => 'xero_commerce']);

                    Route::post('/option/save', ['as' => 'xero_commerce::setting.product.option.save',
                        'uses' => 'ProductOptionController@save',
                        'permission' => 'xero_commerce']);
                    Route::post('/option/remove', ['as' => 'xero_commerce::setting.product.option.remove',
                        'uses' => 'ProductOptionController@remove',
                        'permission' => 'xero_commerce']);
                    Route::get('/option/load/{product}', ['as' => 'xero_commerce::setting.product.option.load',
                        'uses' => 'ProductOptionController@load',
                        'permission' => 'xero_commerce']);

                    Route::get('/{productId}', ['as' => 'xero_commerce::setting.product.show',
                        'uses' => 'ProductController@show',
                        'permission' => 'xero_commerce']);
                    Route::get('/{productId}/edit', ['as' => 'xero_commerce::setting.product.edit',
                        'uses' => 'ProductController@edit',
                        'permission' => 'xero_commerce']);
                    Route::post('/temp', ['as' => 'xero_commerce::setting.product.temp',
                        'uses' => 'ProductController@tempStore',
                        'permission' => 'xero_commerce']);
                    Route::post('/{productId}/update', ['as' => 'xero_commerce::setting.product.update',
                        'uses' => 'ProductController@update',
                        'permission' => 'xero_commerce']);
                    Route::post('/{productId}/remove', ['as' => 'xero_commerce::setting.product.remove',
                        'uses' => 'ProductController@remove',
                        'permission' => 'xero_commerce']);

                    Route::get('/category/child', ['as' => 'xero_commerce:setting.product.category.getChild',
                        'uses' => 'ProductController@getChildCategory',
                        'permission' => 'xero_commerce']);

                });

                //후기, 문의 관리
                Route::get('/communication/feedback', [
                    'as' =>'xero_commerce::setting.commuication.feedback',
                    'uses' => 'CommunicationController@index',
                    'settings_menu' => 'xero_commerce.product.feedback'
                ]);

                Route::get('/communication/qna', [
                    'as' =>'xero_commerce::setting.commuication.qna',
                    'uses' => 'CommunicationController@index',
                    'settings_menu' => 'xero_commerce.product.qna'
                ]);

                Route::get('/communication/show/{type}/{id}', [
                    'as' =>'xero_commerce::setting.communication.show',
                    'uses' => 'CommunicationController@show'
                ]);

                //약관 관리
                Route::get('/agreement', [
                    'as' => 'xero_commerce::setting.agreement.index',
                    'uses' => 'AgreementController@index',
                    'settings_menu' => 'xero_commerce.config.agreement',
                    'permission' => 'xero_commerce'
                ]);

                Route::get('/agreement/{type}', [
                    'as' => 'xero_commerce::setting.agreement.edit',
                    'uses' => 'AgreementController@edit',
                    'permission' => 'xero_commerce'
                ]);

                Route::post('/agreement/{type}', [
                    'as' => 'xero_commerce::setting.agreement.update',
                    'uses' => 'AgreementController@update',
                    'permission' => 'xero_commerce'
                ]);

                //분류 관리
                Route::get('/category', ['as' => 'xero_commerce::setting.category.index',
                    'uses' => 'CategoryController@index',
                    'settings_menu' => 'xero_commerce.product.category',
                    'permission' => 'xero_commerce']);

                //라벨 관리
                Route::group(['prefix' => 'label'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.label.index',
                        'uses' => 'LabelController@index',
                        'settings_menu' => 'xero_commerce.product.label']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.label.create',
                        'uses' => 'LabelController@create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.label.store',
                        'uses' => 'LabelController@store']);
                    Route::post('/update/{id}', ['as' => 'xero_commerce::setting.label.update',
                        'uses' => 'LabelController@update']);
                    Route::get('/edit/{id}', ['as' => 'xero_commerce::setting.label.edit',
                        'uses' => 'LabelController@edit']);
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
                    Route::get('/edit/{badge}', ['as' => 'xero_commerce::setting.badge.edit',
                        'uses' => 'BadgeController@edit']);
                    Route::post('/update/{badge}', ['as' => 'xero_commerce::setting.badge.update',
                        'uses' => 'BadgeController@update']);
                    Route::post('/remove/{id}', ['as' => 'xero_commerce::setting.badge.remove',
                        'uses' => 'BadgeController@remove']);
                });

                //주문 관리
                Route::group([
                    'prefix' => 'orders',
                    'middleware' => ['web']
                ], function () {
                    Route::get('/', [
                        'as' => 'xero_commerce::setting.order.index',
                        'uses' => 'OrderController@index',
                        'settings_menu' => 'xero_commerce.order.index',
                        'permission' => 'xero_commerce'
                    ]);
                    Route::get('/dash', [
                        'as' => 'xero_commerce::setting.order.dash',
                        'uses' => 'OrderController@dash',
                        'settings_menu' => 'xero_commerce.order.dash',
                        'permission' => 'xero_commerce'
                    ]);
                    Route::get('/shipment', [
                        'as' => 'xero_commerce::setting.order.shipment',
                        'uses' => 'OrderController@shipment',
                        'settings_menu' => 'xero_commerce.order.shipment',
                        'permission' => 'xero_commerce'
                    ]);
                    Route::get('/shipment/excel', [
                        'as' => 'xero_commerce::setting.order.shipment.exel',
                        'uses' => 'OrderController@shipmentExcelExport'
                    ]);
                    Route::post('/shipment/excel', [
                        'as' => 'xero_commerce::setting.order.shipment.exel',
                        'uses' => 'OrderController@shipmentExcelImport'
                    ]);
					//02.06 추가
					     Route::get('/shipment/excel1', [
                        'as' => 'xero_commerce::setting.order.shipment.exel',
                        'uses' => 'OrderController@OrderCheckExcelExport'
                    ]);
                    Route::post('/shipment/excel1', [
                        'as' => 'xero_commerce::setting.order.shipment.exel',
                        'uses' => 'OrderController@OrderCheckExcelExport'
                    ]);
					//여기까지
                    Route::post('/shipment', [
                        'as' => 'xero_commerce::process.order.shipment',
                        'uses' => 'OrderController@processShipment',
                        'permission' => 'xero_commerce'
                    ]);
                    Route::post('/shipment/complete', [
                        'as' => 'xero_commerce::complete.order.shipment',
                        'uses' => 'OrderController@completeShipment',
                        'permission' => 'xero_commerce'
                    ]);

                    Route::get('/as', [
                        'as' => 'xero_commerce::setting.order.as',
                        'uses' => 'OrderController@afterservice',
                        'settings_menu' => 'xero_commerce.order.as',
                        'permission' => 'xero_commerce'
                    ]);

                    Route::get('/as/finish/{type}/{orderItem}', [
                        'as' => 'xero_commerce::setting.order.as.finish',
                        'uses' => 'OrderController@afterserviceEnd',
                        'permission' => 'xero_commerce'
                    ]);

                    Route::get('/as/receive/{orderItem}', [
                        'as' => 'xero_commerce::setting.order.as.receive',
                        'uses' => 'OrderController@afterserviceReceive',
                        'permission' => 'xero_commerce'
                    ]);

                    Route::get('/{orderId}', [
                        'as' => 'xero_commerce::setting.order.show',
                        'uses' => 'OrderController@show',
                        'permission' => 'xero_commerce'
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

                    Route::get('/banner', [
                        'as' => 'xero_commerce::setting.config.banner',
                        'uses' => function () {
                            return redirect()->route('banner::group.index');
                        },
                        'settings_menu' => 'xero_commerce.config.banner'
                    ]);

                    Route::get('/user/{keyword}', [
                        'uses' => 'UserController@search',
                        'as' => 'xero_commerce::setting.search.user'
                    ]);

                    Route::get('/shops/{shop}/carriers', [
                        'as' => 'xero_commerce::setting.config.shop.carrier',
                        'uses' => 'ShopController@getCarriers'
                    ]);

                    Route::post('/shops/{shop}/carriers/add', [
                        'as' => 'xero_commerce::setting.config.shop.add.carrier',
                        'uses' => 'ShopController@addCarrier'
                    ]);

                    Route::post('/shops/{shop}/carriers/remove', [
                        'as' => 'xero_commerce::setting.config.shop.remove.carrier',
                        'uses' => 'ShopController@removeCarrier'
                    ]);
                });
            });
        });

        ProductSlugService::setReserved([
            'index', 'create', 'edit', 'update', 'store', 'show', 'remove', 'slug', 'hasSlug',
            'cart', 'order', Plugin::XERO_COMMERCE_URL_PREFIX
        ]);
        \XeRegister::push('settings/permission', 'xero_commerce', [
            'title' => '쇼핑몰관리',
            'tab' => '쇼핑몰관리'
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

        $app->singleton(ProductOptionHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductOptionHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductOptionHandler::class, 'xero_commerce.productOptionHandler');

        $app->singleton(ProductOptionItemHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductOptionItemHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductOptionItemHandler::class, 'xero_commerce.productOptionItemHandler');

        $app->singleton(ProductCustomOptionHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductCustomOptionHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductCustomOptionHandler::class, 'xero_commerce.productCustomOptionHandler');

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

        $app->singleton(WishHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(WishHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(WishHandler::class, 'xero_commerce.wishHandler');

        $app->singleton(QnaHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(QnaHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });

        $app->alias(QnaHandler::class, 'xero_commerce.qnaHandler');

        $app->singleton(FeedbackHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(FeedbackHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });

        $app->alias(FeedbackHandler::class, 'xero_commerce.feedbackHandler');

        $app->singleton(CommunicationHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(CommunicationHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });

        $app->alias(CommunicationHandler::class, 'xero_commerce.communicationHandler');

        $app->singleton(XeroCommerceImageHandler::class, function ($app) {
            return new XeroCommerceImageHandler();
        });
        $app->alias(XeroCommerceImageHandler::class, 'xero_commerce.imageHandler');

        $app->singleton(ValidateManager::class, function ($app) {
            return new ValidateManager();
        });
        $app->alias(ValidateManager::class, 'xero_commerce.validateManager');

        $app->singleton(PaymentHandler::class, function ($app) {
            $uses = XeConfig::getOrNew('xero_pay')->get('uses');
            $useHandler = app('xe.pluginRegister')->get('xero_pay')[$uses]::$handler;
            $proxyHandler = XeInterception::proxy($useHandler);

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

    private static function isShopManager()
    {
        if (Auth::check() === false) return false;
        return Auth::user()->rating === Rating::MANAGER && ShopUser::where('user_id', Auth::id())->exist();
    }

    private static function isSuper()
    {
        if (Auth::check() === false) return false;
        return Auth::user()->rating === Rating::SUPER;
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
                'title' => '뱃지 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100015
            ],
            'xero_commerce.product.feedback' => [
                'title' => '후기관리',
                'display' => true,
                'description' => '',
                'ordering' => 100016
            ],
            'xero_commerce.product.qna' => [
                'title' => '문의관리',
                'display' => true,
                'description' => '',
                'ordering' => 100017
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
            'xero_commerce.order.dash' => [
                'title' => '대시보드',
                'display' => true,
                'description' => '',
                'ordering' => 100021
            ],
            'xero_commerce.order.index' => [
                'title' => '전체 주문내역',
                'display' => true,
                'description' => '',
                'ordering' => 100022
            ],
            'xero_commerce.order.shipment' => [
                'title' => '주문 배송처리',
                'display' => true,
                'description' => '',
                'ordering' => 100023
            ],
            'xero_commerce.order.as' => [
                'title' => '교환/환불 처리',
                'display' => true,
                'description' => '',
                'ordering' => 100024
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
            'xero_commerce.config.agreement' => [
                'title' => '약관 설정',
                'display' => true,
                'description' => '',
                'ordering' => 100034
            ],
            'xero_commerce.config.storeInfo' => [
                'title' => '입점몰 정보',
                'display' => true,
                'description' => '',
                'ordering' => 100035
            ],
            'xero_commerce.config.banner' => [
                'title' => '배너 정보',
                'display' => true,
                'description' => '',
                'ordering' => 100036
            ]
        ];
    }


    public static function interceptGetSettingsMenus()
    {
        intercept(
            SettingsHandler::class . '@getSettingsMenus',
            'homespace_SettingsHandler::getSettingsMenus',
            function ($func, $isSuper) {
                $menus = $func($isSuper);

                /** @var UserInterface $user */
                $user = Auth::user();

                /**
                 * 관리자 메뉴 정리
                 *
                 * 최고 관리자가 아닌 경우 몇개만 보여줌
                 */
                $isSystemAdmin = false;

                if ($user->rating === Rating::SUPER) {
                    $isSystemAdmin = true;
                }

                // 시스템 관리자가 아니면
                if ($isSystemAdmin == false) {
                    $menus->forget(['dashboard', 'sitemap', 'user', 'contents', 'plugin', 'setting', 'lang', 'xeropay']);
                    $xero_commerce = $menus->get('xero_commerce')->getChildren();
                    $xero_commerce->forget(['xero_commerce.config']);
                    $xero_commerce_product = $xero_commerce->get('xero_commerce.product')->getChildren();
                    $xero_commerce_product->forget(['xero_commerce.product.label', 'xero_commerce.product.badge', 'xero_commerce.product.category']);
                }

                return $menus;
            }
        );
    }
}
