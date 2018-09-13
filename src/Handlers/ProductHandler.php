<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

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

    public function getProductsQuery($request)
    {
        $query = new Product();

        $query = $this->settingMakeWhere($request, $query);

        return $query;
    }

    private function settingMakeWhere($request, $query)
    {
        $query = $this->commonMakeWhere($request, $query);

        return $query;
    }

    private function commonMakeWhere($request, $query)
    {
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
