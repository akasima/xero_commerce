@php
    $shipment = new stdClass();
    if($order->orderItems[0]->shipment) {
        $shipment = $order->orderItems[0]->shipment;
    }
@endphp

@section('page_title')
    <h2>주문 상세</h2>
@endsection

<div class="clearfix" style="margin-bottom: 25px;">
    <div class="pull-left">
        <a href="#" onclick="history.back()" class="btn btn-link"><i class="xi-arrow-left"></i> 돌아가기</a>
    </div>
</div>

<div id="component-container" class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body table-responsive">
                    <table class="table">
                        <tr>
                            <th>주문일시</th>
                            <td>{{ $order->created_at }}</td>
                            <th>주문번호</th>
                            <td>{{ $order->order_no }}</td>
                            <th></th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>주문자명</th>
                            <td>{{ $order->userInfo->name }}</td>
                            <th>주문자전화</th>
                            <td>{{ $order->userInfo->phone }}</td>
                            <th>받는사람</th>
                            <td>{{ $shipment->recv_name }}</td>
                        </tr>
                        <tr>
                            <th>송장번호</th>
                            <td>{{ $shipment->ship_no }}</td>
                            <th>배송회사</th>
                            <td>{{ $shipment->carrier->name }}</td>
                            <th>배송일시</th>
                            <td>{{ $shipment->completed_at }}</td>
                        </tr>
                        <tr>
                            <th>주문금액</th>
                            <td>
                            {{
                                number_format(
                                    $order->orderItems->sum(function($item){
                                        return $item->getSellPrice()+$item->getFare();
                                    })
                                )
                            }}
                            </td>
                            <th>입금액</th>
                            <td>{{ number_format($order->payment->price) }}</td>
                            <th>작업</th>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
