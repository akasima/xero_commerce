<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\Wish;

class WishHandler
{
    public function store(SellType $sellType)
    {
        $wish = new Wish();
        $wish->user_id = Auth::id();
        $sellType->wishs()->save($wish);
        $wish->save();
    }

    public function remove(SellType $sellType)
    {
        Wish::where('user_id', Auth::id())
            ->where('type_id', $sellType->id)
            ->where('type_type', get_class($sellType))
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

    public function list($sellType = null)
    {
        $list = Wish::where('user_id', Auth::id())
            ->when($sellType, function ($query) use ($sellType) {
                $query
                    ->where('type_id', $sellType->id)
                    ->where('type_type', get_class($sellType));
            })
            ->with('sellType')
            ->get();

        return $list->map(function (Wish $wish) {
            $selltype = $wish->sellType()->first();
            return [
                'id' => $wish->id,
                'sellType' => $selltype->getJsonFormat(),
                'choose' => []
            ];
        });
    }
}
