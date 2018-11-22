<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 22/11/2018
 * Time: 3:03 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Controllers;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Qna;
use Xpressengine\Plugins\XeroCommerce\Services\QnaService;

class QnaController extends XeroCommerceBasicController
{
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new QnaService();
    }

    public function get(Request $request)
    {
        return $this->service->get($request);
    }

    public function answer(Qna $qna, Request $request)
    {
        $this->service->store($qna, $request);
    }
}
