<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Events\NewProductRegisterEvent;
use Xpressengine\Plugins\XeroStore\Models\Product;

class ProductHandler
{
    /**
     * @param  integer $productId productId
     * @return Product
     */
    public function getProduct($productId)
    {
        $item = Product::where('id', $productId)->first();

        return $item;
    }

    /**
     * @param Request $request request
     *
     * @return Product
     */
    public function getProductsQuery(Request $request)
    {
        $query = new Product();

        $query = $this->settingMakeWhere($request, $query);

        return $query;
    }

    /**
     * @param Request $request request
     * @param Product $query   product
     *
     * @return Product
     */
    private function settingMakeWhere(Request $request, Product $query)
    {
        $query = $this->commonMakeWhere($request, $query);

        return $query;
    }

    /**
     * @param Request $request request
     * @param Product $query   product
     *
     * @return Product
     */
    private function commonMakeWhere(Request $request, Product $query)
    {
        if ($name = $request->get('name')) {
            $query = $query->where('name', 'like', '%' . $name . '%');
        }

        return $query;
    }

    /**
     * @param  array $args productArgs
     * @return integer
     */
    public function store(array $args)
    {
        $newProduct = new Product();

        $newProduct->fill($args);

        $newProduct->save();

        \Event::dispatch(new NewProductRegisterEvent($newProduct));

        return $newProduct->id;
    }
}
