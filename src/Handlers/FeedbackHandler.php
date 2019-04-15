<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 22/11/2018
 * Time: 3:05 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Handlers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Models\FeedBack;
use Xpressengine\Plugins\XeroCommerce\Models\Qna;

class FeedbackHandler
{
    public function store(Model $target, $args)
    {
        $feedBack = new FeedBack();
        $feedBack->fill($args);
        $feedBack->user_id = Auth::id();
        $feedBack->target()->associate($target);
        $feedBack->save();
    }

    public function update(FeedBack $feedBack, $args)
    {
        $feedBack->fill($args);
        $feedBack->save();
    }

    public function delete(FeedBack $feedBack)
    {
        $feedBack->delete();
    }

    public function get(Model $target)
    {
        return FeedBack::where('type_id', $target->id)
            ->where('type_type', get_class($target))
            ->latest()
            ->get();
    }
}
