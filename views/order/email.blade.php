<div class="xe_email">
    <h1>주문완료</h1>
    <p>주문하신 상품이 정상적으로 주문되었습니다.</p>
    <p>자세한 주문정보는 <a href="{{route('xero_commerce::order.detail',['order'=>$order->id])}}">여기</a>서 확인할 수 있습니다. </p>
    <table class="table" style="width: 720px;">
        <thead>
        <tr style="background: #ddd">
            <th colspan="2">상품정보</th>
            <th style="width:15%">결제금액</th>
            <th style="width:15%">수량</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $item)
            @php
                $json= $item->getJsonFormat();
            @endphp
            <tr>
                <td style="width:100px;"><img src="{{$json['src']}}" alt="" style="width:90px; height:120px;"></td>
                <td>{!! implode($item->renderInformation()) !!}</td>
                <td>{{number_format($json['sell_price'])}} 원</td>
                <td>{{$json['count']}} 개</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p>본 메일은 발신전용으로 수신이 불가합니다.</p>
</div>
