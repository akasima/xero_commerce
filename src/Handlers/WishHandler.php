<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Wish;

class WishHandler
{
    public function store(Product $product)
    {
        if(!$this->isWish($product)){
            $wish = new Wish();
            $wish->user_id = Auth::id();
            $product->wishes()->save($wish);
            $wish->save();
        }
    }

    public function storeMany($collection)
    {
        $collection->each(function(Product $product) {
            $this->store($product);
        });
    }

    public function remove(Product $product)
    {
        Wish::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();
    }

    public function removeByModel(Wish $wish)
    {
        $wish->delete();
    }

    public function removeMany($ids)
    {
        Wish::whereIn('id', $ids)->delete();
    }

    public function list($product = null)
    {
        $list = Wish::where('user_id', Auth::id())
            ->when($product, function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->with('product')
            ->get();

        return $list->map(function (Wish $wish) {
            $product = $wish->product()->first();
            return [
                'id' => $wish->id,
                'product' => $product->getJsonFormat(),
                'choose' => []
            ];
        });
    }

    public function isWish(Product $product)
    {
        return
            Wish::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
    }
}
