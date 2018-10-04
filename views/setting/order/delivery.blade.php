<div class="xe-row">
    <div class="xe-col-lg-12">
        <div style="float:right">
            <button class="xe-btn" type="button">엑셀양식다운로드</button>
            <button class="xe-btn" type="button">엑셀업로드</button>
        </div>
    </div>
    <div class="xe-col-lg-12">
        <table class="xe-table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>주문번호</th>
                    <th>상세정보</th>
                    <th>주소</th>
                    <th>배송사</th>
                    <th>송장번호</th>
                    <th>입력완료</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orderItems as $orderItem)
                <tr>
                    <td><input type="checkbox"></td>
                    <td>
                        {{$orderItem->order->order_no}}
                    </td>
                    <td>
                        @foreach($orderItem->renderInformation() as $html)
                        {!! $html !!}
                        @endforeach
                    </td>
                    <td>
                        수령인 : {{$orderItem->delivery->recv_name}} <br>
                        {{$orderItem->delivery->recv_addr. ' ' .$orderItem->delivery->recv_addr_detail}} <br>
                    </td>
                    <td>
                        {{$orderItem->delivery->company->name}}
                    </td>
                    <td>
                        <input type="text">
                    </td>
                    <td>
                        <button>배송중</button>
                        <button>배송완료</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="xe-col-lg-12">
        <div style="float:right">
            <button class="xe-btn xe-btn-black" type="button">일괄배송중</button>
            <button class="xe-btn xe-btn-black" type="button">일괄배송완료</button>
        </div>
    </div>
</div>