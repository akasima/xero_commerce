<?php


namespace Xpressengine\XePlugin\XeroPay;


use App\Facades\XeConfig;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
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
        $pay->is_paid_method=$form->isPaidMethod();
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
        $response = $this->handler->getResponse($request);
        $this->logPayment($response->getPayment(), Payment::REQ, $request->all(), []);
        if($response->fail()) return view('xero_commerce::payment.views.fail',['msg'=>$response->msg()]);


        //거래요청 후 승인 요청(상점->pg)
        $result = $this->handler->getResult($request);
        $this->logPayment($response->getPayment(), Payment::EXE, $request->all(), $result->getInfo());

        //승인 시
        if ($result->success()){
            return view('xero_commerce::payment.views.callback');
        }else {
            return view('xero_commerce::payment.views.fail',['msg'=>$result->msg()]);
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
        $pay = new Payment();
        $pay->user_id = 'test';
        $pay->ip = $request->ip();
        $pay->payment_type = XeConfig::getOrNew('xero_pay')->get('uses');
        $pay->payable_id = $request->get('target')['id'];
        $pay->payable_type = $request->get('target')['type'];
        $pay->name = $request->get('target')['name'];
        $pay->price = $request->get('target')['price'];
        $pay->status = '';
        $pay->method='';
        $pay->info='';
        $pay->is_paid_method=0;
        $pay->save();
        return $pay;
    }

    private function logPayment(Payment $payment, $status, $req =[], $res = [])
    {
        $log = new PayLog();
        $log->req = json_encode($req);
        $log->res = json_encode($res);
        $log->action = $status;
        $payment->status = $status;
        $payment->info=$log->res;
        $payment->log()->save($log);
        $payment->save();
        return $log;
    }

    public function vBank(Request $request)
    {
        $this->handler->vBank($request);
    }
}
