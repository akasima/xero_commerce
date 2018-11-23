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
use Xpressengine\Plugins\XeroCommerce\Models\Qna;

class QnaHandler
{
    public function store(Model $target, $args)
    {
        $qna = new Qna();
        $qna->fill($args);
        $qna->user_id = Auth::id();
        $qna->target()->associate($target);
        $qna->save();
    }

    public function update(Qna $qna, $args)
    {
        $qna->fill($args);
        $qna->save();
    }

    public function delete(Qna $qna)
    {
        $qna->delete();
    }

    public function get(Model $target)
    {
        return Qna::where('type_id', $target->id)
            ->where('type_type', get_class($target))
            ->get();
    }
}
