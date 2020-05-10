
<div class="xero-summary-card">
    <div class="row">
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h4>입금대기<button class="btn btn-default" type="button" @click="downloadTemplate" id="order_excel">엑셀로 추출하기</button></h4>
                    @if($dash['결제대기'])
                        <strong>{{ $dash['결제대기'] }}건</strong>
                    @else
                        <small>교환 요청건이 없네요.</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h4>상품준비</h4>
                    @if($dash['상품준비'])
                        <strong>{{ $dash['상품준비'] }}건</strong>
                    @else
                        <small>배송해야 할 상품이 없어요</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h4>배송중</h4>
                    @if($dash['배송중'])
                        <strong>{{ $dash['배송중'] }}건</strong>
                    @else
                        <small>배송중인 상품이 없어요.</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h4>취소처리</h4>
                    @if($dash['취소중'])
                        <strong>{{ $dash['취소중'] }}건</strong>
                    @else
                        <small>취소 요청건이 없어요!</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h4>환불처리</h4>
                    @if($dash['환불중'])
                        <strong>{{ $dash['환불중'] }}건</strong>
                    @else
                        <small>환불 요청건이 없어요!</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h4>교환처리</h4>
                    @if($dash['교환중'])
                        <strong>{{ $dash['교환중'] }}건</strong>
                    @else
                        <small>교환 요청건이 없어요!</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">주문 현황</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <h4>주문 수</h4>
                <canvas id="orderStat" width="600" height="200">No Canvas</canvas>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body table-scrollable">
        <table class="table table-bordered table-striped" id="orderList">
            <thead>
                <tr>
                    <th rowspan="3"><input type="checkbox"></th>
                    <th colspan="2" rowspan="2">주문번호</th>
                    <th>주문자</th>
                    <th>주문자전화</th>
                    <th>받는분</th>
                    <th rowspan="3">주문합계 <br> 선불배송비포함</th>
                    <th rowspan="3">입금합계</th>
                    <th rowspan="3">주문취소</th>
                </tr>
                <tr>
                    <th>회원ID</th>
                    <th>주문상품수</th>
                    <th>누적주문수</th>
                </tr>
                <tr>
                    <th>주문상태</th>
                    <th>결제수단</th>
                    <th>운송장번호</th>
                    <th>배송회사</th>
                    <th>배송일시</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $item)
                <tr>
                    <td rowspan="3"><input type="checkbox"></td>
                    <td colspan="2" rowspan="2">
                        <a href="{{ route('xero_commerce::setting.order.show', ['orderId' => $item->id]) }}">{{$item->order_no}}</a>
                    </td>
                    <td>{{$item->userInfo->name}}</td>
                    <td>{{$item->userInfo->phone}}</td>
                    <td>@if($item->items[0]->shipment){{$item->items[0]->shipment->recv_name}}@endif</td>
                    <td rowspan="3">{{$item->items->sum(function($item){return $item->getSellPrice()+$item->getFare();})}}</td>
                    <td rowspan="3">{{$item->payment->price}}</td>
                </tr>
                <tr>
                    <td>{{\Xpressengine\User\Models\User::find($item->user_id)->email}}</td>
                    <td>{{$item->items->sum(function($item){return $item->getCount();})}}</td>
                    <td>{{$item->where('user_id',$item->user_id)->count()}}</td>
                </tr>
                <tr>
                    <td>{{$item->getStatus()}}</td>
                    <td>{{$item->payment->getMethod()}}</td>
                    <td>@if($item->items[0]->shipment){{$item->items[0]->shipment->ship_no}}@endif</td>
                    <td>@if($item->items[0]->shipment){{$item->items[0]->shipment->carrier->name}}@endif</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>

	$( "#order_excel").click(function() {//02.07 수정
         location.href='/settings/xero_commerce/order/shipment/excel1';
            });

    $(function(){
        var format = function(date){
            return (date.getMonth()+1) + '/' + date.getDate();
        }
        var ctx=document.getElementById('orderStat');
        var days = {!! json_encode($week['days']) !!};
        var count = {!! json_encode($week['count']) !!};
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [
                {
                    label: '주문수',
                    data: count
                }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            maxTicksLimit: 10
                        }
                    }]
                }
            }
        })
    })
</script>
<style scoped>

</style>
