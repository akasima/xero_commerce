<?php

namespace Xpressengine\Plugins\XeroStore\Controllers;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return \XePresenter::make('xero_store::views.index', ['title' => 'test']);
    }
}
