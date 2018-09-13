<?php

namespace Xpressengine\Plugins\XeroStore\Controllers\Settings;

use XePresenter;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return XePresenter::make('xero_store::views.setting.product.index');
    }
}
