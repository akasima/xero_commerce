<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Tag\TagHandler;
use XeDB;

class ProductManager
{
    /** @var ProductSettingService $productSettingService */
    protected $productSettingService;

    /** @var ProductOptionItemSettingService $productOptionItemSettingService */
    protected $productOptionItemSettingService;

    /** @var TagHandler $tagHandler */
    protected $tagHandler;

    /** @var LabelService $labelService */
    protected $labelService;

    /** @var ProductCategoryService $productCategoryService */
    protected $productCategoryService;

    public function __construct()
    {
        $this->productSettingService = new ProductSettingService();
        $this->productOptionItemSettingService = new ProductOptionItemSettingService();
        $this->productCategoryService = new ProductCategoryService();
        $this->tagHandler = app('xe.tag');
        $this->labelService = new LabelService();
    }

    public function store(Request $request)
    {
        try {
            XeDB::beginTransaction();

            $productId = $this->productSettingService->store($request);
            $this->productOptionItemSettingService->defaultOptionStore($request, $productId);
            $this->setTag($productId, $request);
            ProductSlugService::storeSlug($this->productSettingService->getProduct($productId), $request);
            $this->labelService->createProductLabel($productId, $request);
            $this->productCategoryService->createProductCategory($productId, $request);
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();

        return $productId;
    }

    public function update(Request $request, $productId)
    {
        try {
            XeDB::beginTransaction();

            $this->productSettingService->update($request, $productId);
            $this->setTag($productId, $request);
            ProductSlugService::storeSlug($this->productSettingService->getProduct($productId), $request);
            $this->labelService->updateProductLabel($productId, $request);
            $this->productCategoryService->updateProductCategory($productId, $request);
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();
    }

    public function remove($productId)
    {
        try {
            XeDB::beginTransaction();

            $this->productSettingService->remove($productId);
            $this->productOptionItemSettingService->removeProductOptionItems($productId);
            $this->unSetTag($productId);
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();
    }

    private function setTag($productId, Request $request)
    {
        if ($request->has('_tags') === true) {
            $this->tagHandler->set($productId, $request->get('_tags'), 'xero_commerce');
        }
    }

    private function unSetTag($productId)
    {
        $tags = \XeTag::fetchByTaggable($productId);
        \XeTag::detach($productId, $tags);
    }
}
