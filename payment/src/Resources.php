<?php

namespace Xpressengine\XePlugin\XeroPay;

use App\Facades\XeRegister;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
                    'uses' => 'Controller@formList',
                    'middleware'=>'web'
                ]);
                Route::match(['get', 'post'], '/callback', [
                    'as' => 'xero_pay::callback',
                    'uses' => 'Controller@callback'
                ]);
                Route::get('/', [
                    'as' => 'xero_pay::index',
                    'uses' => 'Controller@index'
                ]);
                Route::get('/close', [
                    'as' => 'xero_pay::close',
                    'uses' => 'Controller@close'
                ]);
                Route::post('/bank', [
                    'as' => 'xero_pay::bank',
                    'uses' => 'Controller@vBank'
                ]);

                Route::get('/cancel', [
                    'as'=>'xero_pay::cancel',
                    'uses'=>'Controller@cancel'
                ]);
            });

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
                    'uses' => 'Controller@setConfig'
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

    public static function addColumn()
    {
        if (!Schema::hasColumn('xero_pay_payment', 'transaction_id')) {
            Schema::table('xero_pay_payment', function (Blueprint $table) {
                $table->string('transaction_id')->nullable();
            });
        }
    }

    public static function makeDataTable()
    {
        if (!Schema::hasTable('xero_pay_payment')) {
            Schema::create('xero_pay_payment', function (Blueprint $table) {
                $table->string('id', 36);
                $table->string('transaction_id')->nullable();
                $table->string('user_id');
                $table->string('name');
                $table->string('ip');
                $table->string('payment_type');
                $table->string('payable_id');
                $table->string('payable_type');
                $table->string('payable_unique_id');
                $table->integer('price');
                $table->string('method');
                $table->boolean('is_paid_method');
                $table->text('info');
                $table->string('status');
                $table->text('receipt');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_pay_log')) {
            Schema::create('xero_pay_log', function (Blueprint $table) {
                $table->increments('id');
                $table->string('status');
                $table->string('payment_id', 36);
                $table->text('req');
                $table->text('res');
                $table->string('action');
                $table->timestamps();
            });
        }
    }

    public static function deleteDataTable(){
        Schema::dropIfExists('xero_pay_payment');

        Schema::dropIfExists('xero_pay_log');
    }
}
