<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Services\ProductOptionItemSettingService;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSettingService;

class ProductOptionController extends Controller
{
    /** @var ProductOptionItemSettingService $optionItemService */
    protected $optionItemService;

    public function __construct()
    {
        $this->optionItemService = new ProductOptionItemSettingService();
    }

    public function save(Request $request)
    {
        if($request->id){
            $this->update($request);
        }else{
            $this->store($request);
        }
    }

    public function store(Request $request)
    {
        $optionItemArgs = $request->all();

        $this->optionItemService->create($optionItemArgs);
    }

    public function update(Request $request)
    {
        $args = $request->all();
        $id = $args['id'];
        $this->optionItemService->update($args,$id);

    }

    public function remove(Request $request)
    {
        $this->optionItemService->remove($request->get('id'));
    }

    public function load(Product $product) {
        $service = new ProductSettingService();
        return $service->getProductOptionArrays($product);
    }
}
