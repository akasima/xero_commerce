<?php

namespace Xpressengine\Plugins\XeroStore\Controllers\Settings;

use App\Http\Controllers\Controller;

class CartControllerController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return \XePresenter::make('xero_store::views.index', ['title' => 'xero_store']);
    }

    public function add()
    {

    }

    public function draw()
    {

    }
}
