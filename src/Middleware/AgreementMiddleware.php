<?php

namespace Xpressengine\Plugins\XeroCommerce\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\UserInfo;
use Xpressengine\Plugins\XeroCommerce\Services\AgreementService;

class AgreementMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (is_null(AgreementService::check('contacts')) || is_null(UserInfo::by(Auth::id()))) {
            return redirect()->guest(route('xero_commerce::agreement.contacts'));
        }

        return $next($request);
    }
}
