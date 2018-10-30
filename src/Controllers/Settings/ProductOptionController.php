<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Services\ProductOptionItemSettingService;

class ProductOptionController extends Controller
{
    /** @var ProductOptionItemSettingService $optionItemService */
    protected $optionItemService;

    public function __construct()
    {
        $this->optionItemService = new ProductOptionItemSettingService();
    }

    public function store(Request $request)
    {
        $optionItemArgs = $request->all();

        $this->optionItemService->create($optionItemArgs);
    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }
}
