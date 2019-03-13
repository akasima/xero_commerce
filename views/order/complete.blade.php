{{--@deprecated since ver 1.1.4--}}
<h2 class="xe-sr-only">쇼핑 단계</h2>

<div class="step-ui step-3">
    <ul>

        <li>
            <p>
                <span class="xe-hidden-sm xe-hidden-xs">01 </span>장바구니 <span class="xe-sr-only">활성화 단계</span>
            </p>
            <div class="tail"></div>
        </li>

        <li>
            <p>
                <span class="xe-hidden-sm xe-hidden-xs">02 </span>주문/결제 <span class="xe-sr-only">활성화 단계</span>
            </p>
            <div class="tail"></div>
        </li>

        <li>
            <p>
                <span class="xe-hidden-sm xe-hidden-xs">03 </span>주문완료 <span class="xe-sr-only">활성화 단계</span>
            </p>
            <div class="tail"></div>
        </li>

    </ul>
</div><!-- //cart-step -->


<div class="xe-shop order">
    <div class="container">
        <div class="order-wrap">
            <!-- [D] 주문 완료 시 노출 -->
            <!-- <h1 class="order-title">주문이 완료되었습니다.</h1> -->

            <!-- [D] 주문 확인 시 노출 -->
            <h1 class="order-title">주문이 확인되었습니다.</h1>
            @if($order->payment->is_paid)
                <p class="order-caption"> 정상적으로 <br class="xe-hidden-lg xe-hidden-md"> 결제가 완료되었습니다. </p>
            @else
                <p class="order-caption"> 정해진 기한까지 은행에 입금해주시면 <br class="xe-hidden-lg xe-hidden-md"> 결제가 완료됩니다. </p>
            @endif

            <div class="order-contents">
                <div class="table-wrap">
                    <h2 class="xe-sr-only">주문번호별 상품 내역</h2>
                    <div class="table-type3">
                        <div class="table-header xe-hidden-sm xe-hidden-xs">
                            <div class="table-row">
                                <div class="table-cell">주문번호</div>
                                <div class="table-cell">상품정보</div>
                            </div>
                        </div> <!-- //table-header -->
                        <div class="table-body" style="min-height:140px">
                            <div class="table-row">
                                <div class="table-cell header">
                                    <h3 class="order-number xe-hidden-md xe-hidden-lg">주문 번호</h3>
                                    <p class="order-number">
                                        <span class="xe-hidden-xs xe-hidden-sm">{{mb_substr($order->created_at,0,10)}}</span>
                                        <br class="xe-hidden-xs xe-hidden-sm">
                                        <a href="#"><span>({{$order->order_no}})</span></a>
                                    </p>
                                    <button class="order-number-btn">
                                        <i class="xi-angle-right-thin"></i>
                                    </button>
                                </div>
                                <div class="table-cell" style="width:100%">

                                    <!-- [D] 재사용 구간 -->
                                    @foreach($order->orderItems as $orderItem)
                                        @php
                                        $json=$orderItem->getJsonFormat();
                                        @endphp
                                        <div class="order-product">
                                            <div class="order-product-title">{{$json['name']}}</div>
                                            <ul class="order-product-option">
                                                @foreach($json['options'] as $option)
                                                    <li>{{$option['unit']['name']}} / {{$option['count']}}개</li>
                                                @endforeach
                                            </ul>
                                        </div> <!-- //order-product -->
                                    @endforeach

                                </div>
                            </div> <!-- //table-row -->
                        </div><!-- //table-body -->
                    </div><!-- //table-type3 -->
                </div><!-- //table-wrap -->

                <div class="order-aside">
                    <div class="order-aside-total">
                        <h1 class="order-aside-total-title">최종 결제금액</h1>
                        <p class="order-aside-total-number">{{number_format($order->payment->price)}}<span>원</span></p>
                    </div><!-- //order-aside-total -->
                    <div class="order-aside-info">
                        <h2 class="order-aside-info-title xe-hidden-lg xe-hidden-md">결제 정보</h2>
                        <div class="order-aside-info-detail">
                            <h3 class="order-aside-info-payment">{{$order->payment->method}}</h3>
                            <ul>
                                @foreach(json_decode($order->payment->receipt) as $key=>$val)
                                    <li>
                                        <span>{{$key}} :</span><span>{{$val}}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div><!-- //order-aside-infor-detail -->
                    </div><!-- //order-aside-infor -->
                </div><!-- //order-aside -->
            </div><!-- //order-contents -->

            <section class="page-btn">
                <h2 class="xe-sr-only">주문 완료 버튼</h2>
                <button type="button" class="page-btn-black" onclick="document.location.href='{{route('xero_commerce::order.index')}}'">구매내역 확인</button>
                <button type="button" class="page-btn-white" onclick="document.location.href='{{url(\Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_MAIN_PAGE_URL)}}'">쇼핑 계속하기</button>
            </section>

        </div><!-- //order-wrap -->
    </div><!-- //container  -->
</div><!-- //cart -->
