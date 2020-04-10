<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Tag\TagHandler;
use XeDB;

class ProductManager
{
    /** @var ProductSettingService $productSettingService */
    protected $productSettingService;

    /** @var ProductOptionSettingService $productOptionSettingService */
    protected $productOptionSettingService;

    /** @var ProductOptionItemSettingService $productOptionItemSettingService */
    protected $productOptionItemSettingService;

    /** @var ProductCustomOptionSettingService $productCustomOptionSettingService */
    protected $productCustomOptionSettingService;

    /** @var TagHandler $tagHandler */
    protected $tagHandler;

    /** @var LabelService $labelService */
    protected $labelService;

    /** @var ProductCategoryService $productCategoryService */
    protected $productCategoryService;

    /**
     * ProductManager constructor.
     */
    public function __construct()
    {
        $this->productSettingService = new ProductSettingService();
        $this->productOptionSettingService = new ProductOptionSettingService();
        $this->productOptionItemSettingService = new ProductOptionItemSettingService();
        $this->productCustomOptionSettingService = new ProductCustomOptionSettingService();
        $this->productCategoryService = new ProductCategoryService();
        $this->tagHandler = app('xe.tag');
        $this->labelService = new LabelService();
    }

    /**
     * @param Request $request request
     *
     * @return int
     *
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validateProduct($request);

        try {
            XeDB::beginTransaction();

            $productId = $this->productSettingService->store($request);
            // 옵션이 수정되었으면 옵션저장, 아니면 기본옵션 입력
            if($request->get('is_option_changed') == 'true') {
                $this->productOptionSettingService->saveOptions($request, $productId);
                $this->productOptionItemSettingService->saveOptionItems($request, $productId);
            } else {
                $this->productOptionItemSettingService->defaultOptionStore($request, $productId);
            }
            // 커스텁옵션이 입력되었다면 저장
            if($request->get('is_custom_option_changed') == 'true') {
                $this->productCustomOptionSettingService->saveOptions($request, $productId);
            }
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

    public function temp(Request $request)
    {

        try {
            XeDB::beginTransaction();
            $productId = (is_null($request->get('id'))) ?
                $this->productSettingService->store($request):
                $request->get('id');
            $this->setTag($productId, $request);
            ProductSlugService::storeSlug($this->productSettingService->getProduct($productId), $request);
            $this->labelService->updateProductLabel($productId, $request);
            $this->productCategoryService->updateProductCategory($productId, $request);
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();

        return $productId;
    }

    /**
     * @param Request $request   request
     * @param int     $productId productId
     *
     * @throws \Exception
     *
     * @return void
     */
    public function update(Request $request, $productId)
    {
        $this->validateProduct($request);

        try {
            XeDB::beginTransaction();

            $this->productSettingService->update($request, $productId);
            if($request->get('is_option_changed') == 'true') {
                $this->productOptionSettingService->saveOptions($request, $productId);
            }
            if($request->get('is_custom_option_changed') == 'true') {
                $this->productCustomOptionSettingService->saveOptions($request, $productId);
            }
            $this->productOptionItemSettingService->saveOptionItems($request, $productId);
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

    /**
     * @param int $productId productId
     *
     * @throws \Exception
     *
     * @return void
     */
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

    /**
     * @param int     $productId productId
     * @param Request $request   request
     *
     * @return void
     */
    private function setTag($productId, Request $request)
    {
        if ($request->has('_tags') === true) {
            $this->tagHandler->set($productId, $request->get('_tags'), 'xero_commerce');
        }
    }

    /**
     * @param int $productId productId
     *
     * @return void
     */
    private function unSetTag($productId)
    {
        $tags = \XeTag::fetchByTaggable($productId);
        \XeTag::detach($productId, $tags);
    }

    private function validateProduct($request)
    {
        app('xero_commerce.validateManager')->productValidate($request);
    }
}
