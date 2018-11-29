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

class Qna extends DynamicModel
{
    protected $table = 'xero_commerce_qna';
    protected $guarded=[];

    public function target ()
    {
        return $this->morphTo('type');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child()
    {
        return $this->morphMany(self::class,'type');
    }

    public function hasGrant()
    {
        if($this->privacy){
            if(Auth::check()){
                return $this->user->id==Auth::id() ||
                Auth::user()->rating===Rating::SUPER ||
                Auth::user()->rating===Rating::MANAGER;
            }else{
                return false;
            }
        }else{
            return true;
        }

    }

    public function jsonSerialize()
    {
        $args = [
            'id'=>$this->id,
            'title'=>($this->hasGrant())?$this->title:'비공개 글입니다.',
            'privacy'=>$this->privacy,
            'content'=>($this->hasGrant())?$this->content:"비공개 글입니다.",
            'writer'=>$this->user->display_name,
            'date'=>$this->created_at->toDateTimeString(),
            'children'=>($this->hasGrant())?$this->child:[],
            'grant'=>$this->hasGrant()
        ];

        return $args;
    }
}
