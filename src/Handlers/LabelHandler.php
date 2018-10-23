<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\ProductLabel;

class LabelHandler
{
    public function store(array $args)
    {
        $newLabel = new Label();

        $newLabel->fill($args);

        $newLabel->save();
    }

    public function destroy($id)
    {
        Label::where('id', $id)->delete();
    }

    public function getLabel($id)
    {
        $label = Label::where('id', $id)->first();

        return $label;
    }

    public function getLabels()
    {
        return Label::get();
    }

    public function storeProductLabel($productId, array $labelIds)
    {
        foreach ($labelIds as $labelId) {
            $newProductLabel = new ProductLabel();

            $newProductLabel->product_id = $productId;
            $newProductLabel->label_id = $labelId;

            $newProductLabel->save();
        }
    }

    public function destroyProductLabel($productId)
    {
        ProductLabel::where('product_id', $productId)->delete();
    }
}
