<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use XePresenter;
use Xpressengine\Plugins\XeroCommerce\Services\LabelService;

class LabelController extends Controller
{
    /** @var LabelService $labelService */
    protected $labelService;

    /**
     * LabelController constructor.
     */
    public function __construct()
    {
        $this->labelService = new LabelService();
    }

    public function index(Request $request)
    {
        $labels = Label::get();

        return XePresenter::make('xero_commerce::views.setting.label.index', compact('labels'));
    }

    public function store(Request $request)
    {
        $this->labelService->create($request);

        return redirect()->route('xero_commerce::setting.label.index');
    }

    public function edit(Request $request, $id)
    {

    }

    public function remove(Request $request, $id)
    {
        $this->labelService->remove($id);

        return redirect()->route('xero_commerce::setting.label.index');
    }
}
