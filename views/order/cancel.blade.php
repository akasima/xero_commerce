{{--@deprecated since ver 1.1.4--}}
<h2 class="xe-sr-only">취소요청</h2>

<div class="step-ui step-1">
    <ul>

        <li>
            <p>
                <span class="xe-hidden-sm xe-hidden-xs">01 </span>취소요청 <span class="xe-sr-only">활성화 단계</span>
            </p>
            <div class="tail"></div>
        </li>

        <li>
            <p>
                <span class="xe-hidden-sm xe-hidden-xs">02 </span>취소요청 완료 <span class="xe-sr-only">활성화 단계</span>
            </p>
            <div class="tail"></div>
        </li>

    </ul>
</div><!-- //cart-step -->
<form method="post" action="{{route('xero_commerce::order.cancel.register',['order'=>$order])}}">
    {{csrf_field()}}
    <div class="xe-shop status">
        <div class="xero-container">
            <div class="status-wrap">

                <h1 class="page-title">취소요청</h1>

                <div class="table-wrap">
                    <h2 class="xe-sr-only">반품상품 정보</h2>
                    <div class="table-type3">
                        <div class="table-header xe-hidden-sm xe-hidden-xs">
                            <div class="table-row">
                                <div class="table-cell cell-date">주문일자</div>
                                <div class="table-cell cell-info">상품정보</div>
                                <div class="table-cell cell-price">결제금액</div>
                                <div class="table-cell cell-num">수량</div>
                                <div class="table-cell cell-shipping">배송비</div>
                            </div>
                        </div> <!-- //table-header -->
                        <div class="table-body">
                            <div class="table-row">
                                <div class="table-cell header cell-date">
                                    <h3 class="order-number xe-hidden-md xe-hidden-lg">주문일자</h3>
                                    <p class="order-number">
                                    <span
                                        class="xe-hidden-xs xe-hidden-sm">{{$order->created_at->format("Y-m-d")}}</span>
                                        <br class="xe-hidden-xs xe-hidden-sm">
                                        <a href="#"><span>({{$order->order_no}})</span></a>
                                    </p>
                                    <button class="order-number-btn">
                                        <i class="xi-angle-right-thin"></i>
                                    </button>
                                </div>
                                <div class="table-cell">

                                    @foreach($order->orderItems as $orderItem)
                                        <div class="table-inner-wrap">
                                            <div class="table-inner-cell cell-info">

                                                <div class="cell-product-info">
                                                    <div class="cell-product-img">
                                                        <img src="{{$orderItem->getThumbnailSrc()}}" alt="상품이미지">
                                                    </div><!-- //cart-product-img -->
                                                    <div class="cell-product-text">
                                                        <div class="cell-product-name">
                                                            {{$orderItem->forcedSellType()->getName()}}
                                                        </div><!-- //cart-product-name -->
                                                        <ul class="cell-product-option">
                                                            @foreach($orderItem->sellGroups as $group)
                                                                <li>
                                                                    <span>{{$group->forcedSellUnit()->getName()}} / {{$group->getCount()}} 개</span>
                                                                </li>
                                                            @endforeach
                                                        </ul><!-- //cell-product-option -->
                                                    </div><!-- //cell-product-text -->
                                                </div>

                                            </div> <!-- //table-inner-cell -->
                                            <div class="table-inner-cell cell-bottom">
                                                <button type="button" class="btn-toggle xe-hidden-md xe-hidden-lg"><i
                                                        class="xi-angle-up-thin"></i><span
                                                        class="xe-sr-only">금액 정보 토글</span></button>

                                                <!-- [D] 모바일 일때 위 버튼 클릭시 display: none 추가 부탁드립니다. -->
                                                <div class="table-inner-wrap">
                                                    <div class="table-inner-cell cell-price">
                                                        <span class="cell-title">결제금액</span>
                                                        <span class="cell-text">{{number_format($orderItem->getSellPrice())}}원</span>

                                                    </div><!-- //table-inner-cell -->
                                                    <div class="table-inner-cell cell-num">
                                                        <span class="cell-title">수량</span>
                                                        <span class="cell-text">{{$orderItem->getCount()}}개</span>
                                                    </div><!-- //table-inner-cell -->
                                                    <div class="table-inner-cell cell-shipping">
                                                        <span class="cell-title">배송비</span>
                                                        <span class="cell-text">{{number_format($orderItem->getFare())}}원</span>
                                                    </div><!-- //table-inner-cell -->
                                                </div><!-- //table-inner-wrap -->
                                            </div><!-- //table-inner-cell -->
                                        </div> <!-- //table-inner-wrap -->
                                    @endforeach
                                    <div class="table-inner-wrap">
                                        <div class="table-inner-cell cell-select">
                                            <p class="cell-select-caption">취소사유</p>
                                        </div> <!-- //table-inner-cell -->
                                        <div class="table-inner-cell cell-textarea">
                                            <textarea class="status-textarea" name="reason"></textarea>
                                        </div><!-- //table-inner-cell -->
                                    </div> <!-- //table-inner-wrap -->
                                </div><!-- //table-cell -->
                            </div> <!-- //table-row -->
                        </div><!-- //table-body -->
                    </div><!-- //table-type3 -->
                </div><!-- //table-wrap -->
                <section class="calc">
                    <h2 class="xe-sr-only">예정 금액</h2>
                    <article class="calc-article">
                        <h3 class="calc-title">취소상품 총 금액</h3>
                        <div class="calc-num">{{number_format($summary['sum'])}}원</div>

                        <h3 class="xe-sr-only">세부내역</h3>
                        <dl class="calc-detail">
                            <dt class="calc-detail-title">상품금액</dt>
                            <dd class="calc-detail-num">{{number_format($summary['sell_price'])}}원</dd>
                            <dt class="calc-detail-title">취소 배송비 합계</dt>
                            <dd class="calc-detail-num">{{number_format($summary['fare'])}}원</dd>
                            <dt class="calc-detail-title">할인금액</dt>
                            <dd class="calc-detail-num">{{number_format($summary['discount_price'])}}원</dd>
                        </dl><!-- //calc-detail -->
                    </article>

                    <article class="calc-article">
                        <h3 class="calc-title">환불금액 차감내역</h3>
                        <div class="calc-num">
                            <div class="icon-sensitization">
                                <i class="xi-minus-min"></i>
                                <span class="xe-sr-only">감소</span>
                            </div>

                            <!-- <div class="icon-sensitization">
                              <i class="xi-plus-min"></i>
                              <span class="xe-sr-only">증가</span>
                            </div> -->
                            0원
                        </div>
                        <h3 class="xe-sr-only">세부내역</h3>
                        <dl class="calc-detail">
                            <dt class="calc-detail-title">배송비 추가금액</dt>
                            <dd class="calc-detail-num">0원</dd>
                            <dt class="calc-detail-title">취소 비용 환불 차감</dt>
                            <dd class="calc-detail-num">0원</dd>
                        </dl><!-- //calc-detail -->
                    </article>

                    <article class="calc-article total">
                        <h3 class="calc-title">환불 예정 금액</h3>
                        <div class="calc-num">{{number_format($summary['sum'])}}원</div>
                        <h3 class="xe-sr-only">세부내역</h3>
                        <div class="calc-detail">
                            <dt class="calc-detail-title">무통장입금 환불금액</dt>
                            <dd class="calc-detail-num">{{number_format($summary['sum'])}}원</dd>
                        </div><!-- //calc-detail -->
                    </article>

                </section> <!-- //calc -->

                <section class="page-btn">
                    <h2 class="xe-sr-only">취소 요청 버튼</h2>
                    <button type="submit" class="page-btn-black">취소요청</button>
                    <button type="submit" class="page-btn-white">이전 페이지</button>
                </section>

            </div><!-- //shipping-wrap -->
        </div><!-- //container  -->
    </div><!-- //shipping -->
</form>
