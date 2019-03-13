{{--@deprecated since ver 1.1.4--}}
<div class="title">{{ $title }}</div>
<div class="xe-well">
    <h2>주문실패</h2>
    <p>결제 단계에서 주문에 실패하였습니다. <a href="{{ route('xero_commerce::order.register.again', ['order_id'=>$order->id]) }}">다시시도</a> 또는 <a href="{{ route('xero_commerce::cart.index') }}">장바구니</a>로 이동합니다.</p>
</div>
