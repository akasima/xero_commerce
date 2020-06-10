@section('page_title')
    <h2>주문 목록</h2>
@endsection

<form method="get" class="form-horizontal" action="{{ route('xero_commerce::setting.order.index') }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row form-group">
                            <div class="col-sm-3">
                                <label>주문번호</label>
                                <input type="text" name="order_no" class="form-control" value="{{ Request::get('order_no') }}">
                            </div>
                            <div class="col-sm-3">
                                <label>조회 기간</label>
                                <div class="input-group">
                                    <input type="text" id="from_date" name="from_date" class="form-control datepicker" value="{{ Request::get('from_date', $fromDate) }}" />
                                    <span class="input-group-addon">~</span>
                                    <input type="text" id="to_date" name="to_date" class="form-control datepicker" value="{{ Request::get('to_date', $toDate) }}" />
                                </div>
                                <div class="text-center">
                                    <div class="btn-group" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default btn-period" data-period="-1w">1주</button>
                                        <button type="button" class="btn btn-default btn-period" data-period="-1m">1개월</button>
                                        <button type="button" class="btn btn-default btn-period" data-period="-3m">3개월</button>
                                        <button type="button" class="btn btn-default btn-period" data-period="-6m">6개월</button>
                                        <button type="button" class="btn btn-default btn-period" data-period="-1y">1년</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label>송장번호</label>
                                <input type="text" name="ship_no" class="form-control" value="{{ Request::get('ship_no') }}">
                            </div>
                            <div class="col-sm-3">
                                <label>배송 상태</label>
                                <select name="code" class="form-control">
                                    <option value="">선택</option>
                                    @foreach(\Xpressengine\Plugins\XeroCommerce\Models\Order::STATUS as $i => $status)
                                        <option value="{{ $i }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-sm-3">
                                <label>주문자명</label>
                                <input type="text" name="user_name" class="form-control" value="{{ Request::get('user_name') }}">
                            </div>
                            <div class="col-sm-3">
                                <label>주문자전화</label>
                                <input type="text" name="user_phone" class="form-control" value="{{ Request::get('user_phone') }}">
                            </div>
                            <div class="col-sm-3">
                                <label>받는사람</label>
                                <input type="text" name="recv_name" class="form-control" value="{{ Request::get('recv_name') }}">
                            </div>
                            <div class="col-sm-3">
                                <label>받는사람전화</label>
                                <input type="text" name="recv_phone" class="form-control" value="{{ Request::get('recv_phone') }}">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <a href="{{ route('xero_commerce::setting.order.index') }}" class="xe-btn xe-btn-link">초기화</a>
                            <button type="submit" class="xe-btn xe-btn-primary">검색</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="text-right panel-heading">
                    <button class="xe-btn xe-btn-primary" id="downloadExcel">엑셀 다운로드 </button>
                </div>
                <div class="panel-body table-scrollable">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>주문일시</th>
                            <th>주문번호</th>
                            <th>주문자명</th>
                            <th>주문자전화</th>
                            <th>받는사람</th>
                            <th>송장번호</th>
                            <th>배송회사</th>
                            <th>배송일시</th>
                            <th>주문금액</th>
                            <th>입금액</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            @php
                                $shipment = new stdClass();
                                if($order->items[0]->shipment) {
                                    $shipment = $order->items[0]->shipment;
                                }
                            @endphp
                            <tr>
                                <td class="nowrap">{{ $order->created_at }}</td>
                                <td class="nowrap">
                                    <a href="{{ route('xero_commerce::setting.order.show', ['orderId' => $order->id]) }}">{{ $order->order_no }}</a>
                                </td>
                                <td class="nowrap">{{ $order->userInfo->name }}</td>
                                <td class="nowrap">{{ $order->userInfo->phone }}</td>
                                <td class="nowrap">{{ $shipment->recv_name }}</td>
                                <td class="nowrap">{{ $shipment->ship_no }}</td>
                                <td class="nowrap">{{ $shipment->carrier->name }}</td>
                                <td class="nowrap">{{ $shipment->completed_at }}</td>
                                <td class="nowrap">
                                    {{
                                        number_format(
                                            $order->items->sum(function($item){
                                                return $item->getSellPrice()+$item->getFare();
                                            })
                                        )
                                    }}
                                </td>
                                <td class="nowrap">{{ number_format($order->payment->price) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/i18n/jquery.ui.datepicker-ko.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<script>
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.btn-period').click(function(e) {
        var period = $(this).data('period');
        var $from = $('#from_date');
        var $to = $('#to_date');

        // 현재 날짜로 초기화
        $to.datepicker('setDate', new Date());
        // 버튼에 설정된 period (+1d 와 같은 형식)으로 계산
        $from.datepicker('setDate', period);
    });
    $('#downloadExcel').click(function(){
        var form_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        window.open('orders/excel?from_date='+form_date+'&to_date='+to_date,"_blank");
    });
</script>
