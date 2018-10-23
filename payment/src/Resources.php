<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 23/10/2018
 * Time: 3:00 PM
 */

namespace Xpressengine\XePlugin\XeroPay;

use App\Facades\XeRegister;
use Route;

class Resources
{
    public static function registerRoute()
    {
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
                Route::get('/', [
                    'as' => 'xero_pay::index',
                    'uses' => 'Controller@index'
                ]);
            });
//
        Route::settings('xero_pay', function () {
            Route::group([
                'namespace' => 'Xpressengine\\XePlugin\\XeroPay'
            ], function () {
                Route::get('/', [
                    'as' => 'xero_pay::index',
                    'uses' => 'Controller@index',
                    'settings_menu' => 'xeropay'
                ]);
                Route::post('/', [
                    'as' => 'xero_pay::post',
                    'uses'=>'Controller@setConfig'
                ]);
            });
        });

    }

    public static function registerMenu()
    {
        XeRegister::push('settings/menu', 'xeropay', [
            'title' => '결제 관리',
            'description' => '결제관리용',
            'display' => true,
            'ordering' => 20000
        ]);
    }
}
