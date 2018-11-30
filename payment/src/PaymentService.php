<?php


namespace Xpressengine\XePlugin\XeroPay;


use App\Facades\XeConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xpressengine\Http\Request;
use Xpressengine\User\Rating;
use Xpressengine\XePlugin\XeroPay\Models\PayLog;
use Xpressengine\XePlugin\XeroPay\Models\Payment;

class PaymentService
{

    /**
     * @var PaymentHandler
     */
    protected $handler;

    public function __construct()
    {
        $this->handler = app('xero_pay::paymentHandler');
    }

    public function getPg()
    {
        return app('xe.pluginRegister')->get('xero_pay');
    }

    public function loadScript()
    {
        $this->handler->prepare();
    }

    public function statusCheck()
    {
        $config = XeConfig::getOrNew('xero_pay');
        $uses = $config->get('uses');
        $items = app('xe.pluginRegister')->get('xero_pay')[$uses]::configItems();
        foreach($items as $key=>$item)
        {
            $propertyName = implode('.',[
                'pg',
                $uses,
                $key
            ]);
            $propertyValue = $config->get($propertyName);
            if(is_null($propertyValue)){
                $msg = "결제 시스템에 문제가 있습니다. 관리자에게 문의 바랍니다.";
                if(Auth::user()->rating===Rating::SUPER){
                    $msg .= "<br>[관리자의 경우 <a href='".route('xero_pay::index')."'>결제관리</a>를 확인해주세요.]";
                    $msg .= "<br>(위 메세지는 관리자 아이디에만 표시됩니다.)";
                }
                abort(500,$msg);
            }
        }
    }

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function formatRequest(Request $request)
    {
        $pay = $this->makePayment($request);
        $form = $this->handler->makePaymentRequest($request, $pay);
        $this->logPayment($pay, Payment::CREATE, $request->all(), $form);
        $pay->method = $form->getMethod();
        $pay->is_paid_method = $form->isPaidMethod();
        $pay->save();
        return $form;
    }

    /**
     * @param Request $request
     * @return PaymentResponse
     */
    public function execute(Request $request)
    {
        //거래요청(고객->pg)
        if($request->all())
        $response = $this->handler->getResponse($request);
        $payment = $response->getPayment();
        $this->logPayment($payment, Payment::REQ, $request->all(), []);
        if ($response->fail()) return view('xero_commerce::payment.views.fail', ['msg' => ‌‌addslashes(strip_tags($response->msg()))]);


        //거래요청 후 승인 요청(상점->pg)
        $result = $this->handler->getResult($request);
        $this->logPayment($payment, Payment::EXE, $request->all(), $result->getInfo());
        $payment->receipt = $result->getReceipt();
        $payment->transaction_id = $result->getUniqueNo();
        $payment->save();

        //승인 시
        if ($result->success()) {
            return view('xero_commerce::payment.views.callback');
        } else {
            return view('xero_commerce::payment.views.fail', ['msg' => $result->msg()]);
//            return '<script>alert("' . $result->msg() . '"); parent.closeIframe();</script>';
        }
    }

    public function callback(Request $request)
    {
        return $this->handler->callback($request);
    }

    public function methodList()
    {
        return $this->handler->getMethodList();
    }

    private function makePayment(Request $request)
    {
        $pay = Payment::firstOrCreate([
            'payable_id' => $request->get('target')['id'],
            'payable_type' => $request->get('target')['type']
        ], [
            'payable_unique_id'=>$request->get('target')['uniqueId'],
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'payment_type' => XeConfig::getOrNew('xero_pay')->get('uses'),
            'name' => $request->get('target')['name'],
            'price' => $request->get('target')['price'],
            'status' => '',
            'method' => '',
            'info' => '',
            'is_paid_method' => 0
        ]);
        return $pay;
    }

    private function logPayment(Payment $payment = null, $status, $req = [], $res = [])
    {
        if(is_null($payment)){
            Log::error('payment_error_info:');
            Log::error($req);
            Log::error($res);
            return;
        }
        $log = new PayLog();
        $log->req = json_encode($req);
        $log->res = json_encode($res);
        $log->action = $status;
        $payment->status = $status;
        $payment->info = $log->res;
        $payment->log()->save($log);
        $payment->save();
        return $log;
    }

    public function vBank(Request $request)
    {
        $this->handler->vBank($request);
    }

    public function cancel($payable, $reason)
    {
        $payment = $payable->xeropay;
        $cancel = $this->handler->cancel($payment, $reason);
        $this->logPayment($payment, Payment::CANCEL, [$reason], json_encode($cancel->getInfo()));
        if ($cancel->fail()) return $cancel->msg();
        return true;
    }
}
