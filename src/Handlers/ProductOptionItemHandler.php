<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItemRevision;

class ProductOptionItemHandler
{
    public function store(array $args)
    {
        $newProductOptionItem = new ProductOptionItem();

        $newProductOptionItem->fill($args);

        $newProductOptionItem->save();

        $this->storeRevision($newProductOptionItem);
    }

    public function getOptionItem($productOptionItemId)
    {
        $item = ProductOptionItem::where('id', $productOptionItemId)->first();

        return $item;
    }

    public function destroy(ProductOptionItem $item)
    {
        $item->delete();

        $this->storeRevision($item);
    }

    public function update(ProductOptionItem $optionItem, array $args)
    {
        $attributes = $optionItem->getAttributes();
        foreach ($args as $key => $value) {
            if (array_key_exists($key, $attributes) === true) {
                $optionItem->{$key} = $value;
            }
        }

        $optionItem->save();

        $this->storeRevision($optionItem);
    }

    private function storeRevision($optionItem)
    {
        $revisionNo = 0;
        $lastRevision = ProductOptionItemRevision::where('id', $optionItem->id)->max('revision_no');
        if ($lastRevision !== null) {
            $revisionNo = $lastRevision + 1;
        }

        $revisionOptionItem = new ProductOptionItemRevision();

        $revisionOptionItem->fill($optionItem->getAttributes());

        $revisionOptionItem->id = $optionItem->id;
        $revisionOptionItem->revision_no = $revisionNo;
        $revisionOptionItem->origin_deleted_at = $optionItem->deleted_at;
        $revisionOptionItem->origin_created_at = $optionItem->created_at;
        $revisionOptionItem->origin_updated_at = $optionItem->updated_at;

        $revisionOptionItem->save();
    }
}
