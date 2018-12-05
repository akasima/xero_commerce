<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

class BadgeHandler
{
    public function store($args)
    {
        $newBadge = new Badge();

        $newBadge->fill($args);

        $newBadge->save();
    }

    public function update($badge, $args)
    {

        $badge->fill($args);

        $badge->save();
    }

    public function destroy($id)
    {
        Badge::where('id', $id)->delete();
        Product::where('badge_id', $id)->update([
            'badge_id' => null
        ]);
    }
}
