<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 05/11/2018
 * Time: 12:35 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Controllers;


use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Services\WishService;

class WishController extends XeroCommerceBasicController
{
    protected $wishService;
    public function __construct()
    {
        parent::__construct();
        $this->wishService = new WishService();
    }

    public function index()
    {
        $list = $this->wishService->get();
        return \XePresenter::make('xero_commerce::views.wish.index', ['list'=>$list]);
    }

    public function remove(Request $request)
    {
        $this->wishService->removeMany($request);
    }
}
