<?php

namespace Xpressengine\Plugins\XeroCommerce\Controllers\Settings;

use App\Http\Controllers\Controller;
use Xpressengine\Permission\Grant;
use Xpressengine\User\Models\User;

class UserController extends SettingBaseController
{
    public function search($keyword)
    {
        return User::where(function ($query) use ($keyword) {
            $query->where('display_name', 'like', "{$keyword}%")->orWhere('email', 'like', "{$keyword}%");
        })->where('rating', '!=', Grant::USER_TYPE)->get();
    }
}
