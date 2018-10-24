<?php

namespace Xpressengine\Plugins\XeroCommerce\Logger;

use Illuminate\Contracts\Foundation\Application;
use Xpressengine\Http\Request;
use Xpressengine\Log\AbstractLogger;
use Xpressengine\Log\Models\Log;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceLogger extends AbstractLogger
{
    const ID = Plugin::XERO_COMMERCE_PREFIX;

    const TITLE = Plugin::XERO_COMMERCE_PREFIX;

    public function initLogger(Application $app)
    {
        $app['events']->listen('Illuminate\Foundation\Http\Events\RequestHandled', function ($result) {
            if ($result->request->route() == null) {
                return;
            }

            $request = $result->request;

            self::writeLog($request, self::getSummary($request));
        });
    }

    private function writeLog(Request $request, $summary)
    {
        if ($summary === null) {
            return;
        }

        $data = $this->loadRequest($request);
        array_set($data['data'], 'route', $request->route()->getName());
        array_forget($data['parameters'], 'password');
        array_set($data['data'], 'user_id', $request->route()->parameter('id'));
        $data['summary'] = $summary;

        $this->log($data);
    }

    public function renderDetail(Log $log)
    {
        return null;
    }

    private function getSummary(Request $request)
    {
        $list = [
            'xero_commerce::setting.order.index' => '주문 내역 열람'
        ];

        return $list[$request->route()->getName()] ?? null;
    }
}
