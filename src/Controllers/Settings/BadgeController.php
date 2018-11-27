<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use XePresenter;
use Xpressengine\Plugins\XeroCommerce\Services\BadgeService;

class BadgeController extends SettingBaseController
{
    /** @var BadgeService $badgeService */
    protected $badgeService;

    public function __construct()
    {
        parent::__construct();
        $this->badgeService = new BadgeService();
    }

    public function index(Request $request)
    {
        $badges = Badge::get();

        return XePresenter::make('xero_commerce::views.setting.badge.index', compact('badges'));
    }

    public function edit(Request $request, Badge $badge)
    {
        return XePresenter::make('xero_commerce::views.setting.badge.edit', compact('badge'));
    }

    public function store(Request $request)
    {
        $this->badgeService->create($request);

        return redirect()->route('xero_commerce::setting.badge.index');
    }

    public function update(Request $request, Badge $badge)
    {
        $this->badgeService->update($request, $badge);

        return redirect()->route('xero_commerce::setting.badge.index');
    }

    public function remove(Request $request, $id)
    {
        $this->badgeService->remove($id);

        return redirect()->route('xero_commerce::setting.badge.index');
    }
}
