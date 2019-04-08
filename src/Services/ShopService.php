<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use App\Facades\XeMedia;
use App\Facades\XeStorage;
use Illuminate\Support\Collection;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Image;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;

class ShopService
{
    /** @var ShopHandler $handler */
    protected $handler;

    /**
     * ShopService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.shopHandler');
    }

    /**
     * @param Request $request request
     *
     * @return Collection
     */
    public function getShops(Request $request)
    {
        $conditions = $request->all();

        $query = $this->handler->getShopsQuery($conditions);

        //TODO pagination 추가
        $shops = $query->get();

        return $shops;
    }

    /**
     * @param string $userId userId
     *
     * @return Shop
     */
    public function getMyShops($userId)
    {
        $args['user_id'] = $userId;

        $query = $this->handler->getShopsQuery($args);

        $shops = $query->get();

        return $shops;
    }

    /**
     * @param Request $request request
     *
     * @return Shop
     */
    public function create(Request $request)
    {
        $this->validateShop($request);

        $args = $request->all();

        $args['logo_path'] = $this->saveImage($request->get('logo'))->id;

        $args['state_approval'] = Shop::APPROVAL_WAITING;

        $newShop = $this->handler->store($args);

        return $newShop;
    }

    /**
     * @param int $shopId shopId
     *
     * @return mixed
     */
    public function getShop($shopId)
    {
        $shop = $this->handler->getShop($shopId);

        return $shop;
    }

    /**
     * @param Request $request request
     * @param int     $shopId  shopId
     *
     * @return void
     */
    public function update(Request $request, $shopId)
    {
        $this->validateShop($request);

        $args = $request->all();
        $args['logo_path'] = $this->saveImage($request->get('logo'))->id;

        $shop = $this->handler->getShop($shopId);

        $this->handler->update($args, $shop);
        $shop->users()->sync($args['user_id']);
    }

    /**
     * @param int $shopId shopId
     *
     * @return bool
     */
    public function remove($shopId)
    {
        $shop = $this->handler->getShop($shopId);

        if ($shop->isBasicShop() === true) {
            return false;
        }

        $this->handler->destroy($shopId);

        return true;
    }

    public function addDelivery(Request $request, Shop $shop)
    {
        $args = $request->all();
        $this->handler->addDelivery($args, $shop);
    }

    public function removeDelivery(Request $request, Shop $shop)
    {
        $args = $request->all();
        $this->handler->removeDelivery($args, $shop);
    }

    protected function validateShop(Request $request)
    {
        app('xero_commerce.validateManager')->shopValidate($request);
    }

    public function saveImage($imageParm)
    {
        if(is_null($imageParm)){
            return new Image();
        }
        $file = XeStorage::upload($imageParm, 'public/xero_commerce/logo');
        $imageFile = XeMedia::make($file);
        XeMedia::createThumbnails($imageFile, 'fit');

        return $imageFile;
    }
}
