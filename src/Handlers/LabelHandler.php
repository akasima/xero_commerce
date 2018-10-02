<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\ProductLabel;

class LabelHandler
{
    public function storeProductLabel($productId, array $labelIds)
    {
        foreach ($labelIds as $labelId) {
            $newLabel = new ProductLabel();

            $newLabel->product_id = $productId;
            $newLabel->label_id = $labelId;

            $newLabel->save();
        }
    }

    public function destroyProductLabel($productId)
    {
        ProductLabel::where('product_id', $productId)->delete();
    }
}
