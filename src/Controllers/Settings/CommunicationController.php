<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 12/12/2018
 * Time: 6:38 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;


use App\Facades\XePresenter;
use Xpressengine\Plugins\XeroCommerce\Services\CommunicationManageService;

class CommunicationController
{
    private $communicationService;

    public function __construct()
    {
        $this->communicationService=new CommunicationManageService();
    }

    public function index()
    {
        $type = basename(url()->current());
        $list = $this->communicationService->getList($type);
        return XePresenter::make('xero_commerce::views.setting.communication.index', compact('list' , 'type'));
    }

    public function update()
    {
        return redirect()->route('xero_commerce::setting.communication.show');
    }

    public function show($type, $id)
    {
        $item = $this->communicationService->getItem($type, $id);
        $target = $this->communicationService->getTargetInfo($item);
        return XePresenter::make('xero_commerce::views.setting.communication.show', compact('item', 'type', 'target'));
    }
}
