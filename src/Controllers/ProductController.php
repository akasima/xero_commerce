<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return \XePresenter::make('xero_store::views.index', ['title' => 'test']);
    }
}
