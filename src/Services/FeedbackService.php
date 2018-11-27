<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 22/11/2018
 * Time: 3:05 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Services;


use Illuminate\Database\Eloquent\Model;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Controllers\FeedbackController;
use Xpressengine\Plugins\XeroCommerce\Handlers\QnaHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Qna;

class FeedbackService
{
    /**
     * @var FeedbackController
     */
    protected $handler;

    public function __construct()
    {
        $this->handler = app('xero_commerce.feedbackHandler');
    }

    public function store(Model $target, Request $request)
    {
        $args = $request->only(['title','content', 'score']);
        $this->handler->store($target, $args);
    }

    public function update(Qna $qna, Request $request)
    {
        $args = $request->all();
        $this->handler->update($qna, $args);
    }

    public function delete(Qna $qna)
    {
        $this->handler->delete($qna);
    }

    public function get(Request $request)
    {
        $type = $request->get('type');
        $id=$request->get('id');
        return $this->handler->get($type::find($id));
    }

    public function getTargetFeedback(Model $target)
    {
        return $this->handler->get($target);
    }
}
