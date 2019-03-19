<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use XePresenter;
use Xpressengine\Plugins\XeroCommerce\Services\LabelService;

class LabelController extends SettingBaseController
{
    /** @var LabelService $labelService */
    protected $labelService;

    /**
     * LabelController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->labelService = new LabelService();
    }

    public function index(Request $request)
    {
        $labels = Label::get();

        return XePresenter::make('label.index', compact('labels'));
    }

    public function store(Request $request)
    {
        $this->labelService->create($request);

        return redirect()->route('label.index');
    }

    public function edit(Request $request, $id)
    {
        $label = Label::find($id);
        return XePresenter::make('setting.label.edit', compact('label'));
    }

    public function update(Request $request, $id)
    {
        $label = Label::find($id);
        $this->labelService->update($request, $label);
        return redirect()->route('xero_commerce::setting.label.index');
    }

    public function remove(Request $request, $id)
    {
        $this->labelService->remove($id);

        return redirect()->route('xero_commerce::setting.label.index');
    }
}
