<h2>장바구니</h2>
<form action="{{route('xero_commerce::order.register')}}" method="post">
    {{csrf_field()}}

    <table class="xe-table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox">
                </th>
                <th></th>
                <th>상품정보</th>
                <th>상품금액</th>
                <th>할인금액</th>
                <th>수량</th>
                <th>배송비</th>
                <th>주문금액</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carts as $cartProduct)
                <tr>
                    <td>
                        <input type="checkbox" name="product_id[]" value="{{$cartProduct->first()->option->product->id}}" checked="checked">
                    </td>
                    <td><img src="https://www.xpressengine.io/plugins/official_homepage/assets/theme/img/feature_02.jpg" width="150px" height="150px" alt=""></td>
                    <td>
                        {{$cartProduct->first()->option->product->name}} <button class="btn btn-default">옵션변경</button>
                        <br>
                        @foreach($cartProduct as $cartOption)
                        <span style="color:grey">{{$cartOption->option->name}} / {{$cartOption->count}} 개</span>
                            <a href="{{route('xero_commerce::cart.draw', ['cart'=>$cartOption->id])}}">x 해당 옵션 삭제</a>
                            <br>
                        @endforeach
                        쇼핑몰 : {{$cartProduct->first()->option->product->store->store_name}}
                    </td>
                    <td>
                        {{number_format($cartProduct->sum('option.product.original_price'))}} 원
                    </td>
                    <td>
                        {{number_format($cartProduct->sum('option.product.sell_price'))}} 원
                    </td>
                    <td>
                        {{$cartProduct->sum('count')}} 개
                    </td>
                    <td>
                        선불
                    </td>
                    <td>
                        <b>{{number_format($cartProduct->sum('option.product.original_price') - $cartProduct->sum('option.product.sell_price'))}} 원</b> <br>
                        <button class="btn xe-btn-black">주문하기</button>
                        <button class="btn btn-default">삭제하기</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="xe-btn">선택상품 삭제</button>
    <button class="xe-btn">선택상품 찜</button>
    <hr>
    <table class="xe-table">
        <tbody>
            <tr>
                <th rowspan="2">장바구니결제정보</th>
                <td>상품금액</td>
                <td>할인금액</td>
                <td>배송금액</td>
                <td class="text-left">결제 예정 금액</td>
            </tr>
            <tr>
                <td>{{number_format($summary['original_price'])}} 원</td>
                <td>{{number_format($summary['sell_price'])}} 원</td>
                <td>{{number_format($summary['fare'])}} 원 <br>
                    <p>적립금 혜택 100원</p>
                </td>
                <td>{{number_format($summary['sum'])}} 원</td>
            </tr>
        </tbody>
    </table>
    <div>
        <button class="xe-btn xe-btn-black xe-btn-lg" type="submit">구매하기</button>
        <button class="xe-btn xe-btn-lg">쇼핑 계속하기</button>
    </div>
</form>
<script>
    var submit = () => {

    }
</script>