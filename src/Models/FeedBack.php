<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 22/11/2018
 * Time: 3:06 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Models;


use Illuminate\Support\Facades\Auth;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\User\Models\User;
use Xpressengine\User\Rating;

class FeedBack extends DynamicModel
{
    protected $table = 'xero_commerce__feedbacks';
    protected $guarded=[];

    public function target ()
    {
        return $this->morphTo('type');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jsonSerialize()
    {
        $args = [
            'id'=>$this->id,
            'title'=>$this->title,
            'content'=>$this->content,
            'writer'=>$this->user->display_name,
            'date'=>$this->created_at->toDateTimeString(),
            'score'=>$this->score
        ];

        return $args;
    }
}
