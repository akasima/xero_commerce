<template>
    <section class="shipping-result">
        <h2 class="xe-sr-only">조회 결과</h2>

        <div class="table-wrap" v-if="list.length>0">
            <h2 class="xe-sr-only">주문번호별 상품 내역</h2>
            <div class="table-type3">
                <div class="table-header xe-hidden-sm xe-hidden-xs">
                    <div class="table-row">
                        <div class="table-cell ordernum">주문일자</div>
                        <div class="table-cell info">상품정보</div>
                        <div class="table-cell pay">결제금액</div>
                        <div class="table-cell counter">수량</div>
                        <div class="table-cell shipping">배송비</div>
                        <div class="table-cell status">주문상태</div>
                    </div>
                </div> <!-- //table-header -->
                <div class="table-body">

                    <!-- [D] 복사 영역 주문별 영역 -->
                    <div class="table-row"  v-for="order in list">
                        <div class="table-cell header">
                            <h3 class="order-number xe-hidden-md xe-hidden-lg">주문 번호</h3>
                            <p class="order-number">
                                <span class="xe-hidden-xs xe-hidden-sm">{{order.created_at.substr(0,10)}}</span>
                                <br class="xe-hidden-xs xe-hidden-sm">
                                <a :href="detailUrl+'/'+order.id"><span>({{order.order_no}})</span></a>
                            </p>
                            <button class="order-number-btn">
                                <i class="xi-angle-right-thin"></i>
                            </button>
                        </div>
                        <div class="table-cell">

                            <!-- [D] 복사 영역 상품 블럭 -->
                            <div class="shipping-product"  v-for="(orderItem, key) in order.items">
                                <div class="cart-product-body">
                                    <div class="cart-product-info">
                                        <div class="cart-product-img">
                                            <img :src="orderItem.src" alt="상품이미지">
                                        </div><!-- //cart-product-img -->
                                        <div class="cart-product-name">
                                            {{orderItem.name}}
                                        </div><!-- //cart-product-name -->
                                        <div class="cart-product-option">
                                            <ul class="cart-product-option-list">
                                                <li>
                                                    <span>
                                                      {{orderItem.variant_name}}
                                                      <span v-for="(option, i) in orderItem.custom_options">
                                                        {{ i == 0 ? '(' : '' }}
                                                        {{option.name}} : {{option.display_value}}
                                                        {{ i != Object.keys(orderItem.custom_options).length - 1 ? ',' : ')' }}
                                                      </span>
                                                      / {{orderItem.count}}개
                                                    </span>
                                                </li>
                                            </ul>
                                        </div><!-- //cart-product-option -->
                                    </div><!-- //cart-product-info -->
                                    <div class="cart-product-num">
                                        <div class="cart-product-num-box">
                                            <div class="cart-product-price">
                                                <span class="cart-product-title">결제금액</span>
                                                <span class="cart-product-text">{{orderItem.sell_price.toLocaleString()}}원</span>
                                            </div><!-- //cart-product-price -->
                                            <div class="cart-product-quantity">
                                                <span class="cart-product-title">수량</span>
                                                <span class="cart-product-text">{{orderItem.count}}개</span>
                                            </div><!-- //cart-product-num -->
                                            <div class="cart-product-shipping">
                                                <span class="cart-product-title">배송비</span>
                                                <span class="cart-product-text">{{orderItem.fare.toLocaleString()}}원</span>
                                            </div><!-- //cart-product-shipping -->
                                            <div class="cart-product-sum">
                                                <span class="cart-product-title">주문 상태</span>
                                                <span class="cart-product-text">{{order.status}}</span>
                                                <div class="cart-product-btn">
                                                    <button v-if="inDelivery(order)" type="button" class="btn-shipping-status" @click="url(orderItem.delivery_url)">배송조회</button>
                                                    <div v-if="inDelivery(order) && !notInProcess(order)">
                                                        <button type="button" class="btn-shipping-status" @click="url(asUrl+'/change/'+order.id+'/'+orderItem.id)">교환요청</button>
                                                        <button type="button" class="btn-shipping-status" @click="url(asUrl+'/change/'+order.id+'/'+orderItem.id)">환불요청</button>
                                                    </div>
                                                    <button v-if="notInDelivery(order) && !notInProcess(order)" type="button" class="btn-shipping-status" @click="url(cancelUrl+'/'+order.id)">취소요청</button>
                                                </div><!-- //cart-product-btn -->
                                            </div><!-- //cart-product-sum -->
                                        </div><!-- //cart-product-num-box -->
                                    </div><!-- //cart-product-num -->
                                </div> <!-- //cart-product-body -->
                            </div><!-- //shipping-product -->
                        </div>
                    </div> <!-- //table-row -->
                </div><!-- //table-body -->
            </div><!-- //table-type3 -->
            <div class="container pagination" v-if="paginate">
                <div style="margin:0 auto">
                    <div style="position:relative">
                        <div style="position:absolute; left:0; bottom:0">
                            total : {{currentCount}} / {{paginate.total}} ({{paginate.from}} ~ {{paginate.to}})
                        </div>
                        <div style="margin:0 auto; width:50%; text-align: center">
                            <a @click="$emit('page',1)">처음</a>
                            <a v-if="paginate.current_page-2>0" @click="$emit('page',paginate.current_page-2)">{{paginate.current_page-2}}</a>
                            <a v-if="paginate.current_page-1>0" @click="$emit('page',paginate.current_page-1)">{{paginate.current_page-1}}</a>
                            <b><a @click="$emit('page',first_page)">{{paginate.current_page}}</a></b>
                            <a v-if="paginate.current_page+1<=paginate.last_page"
                               @click="$emit('page',paginate.current_page+1)">{{paginate.current_page+1}}</a>
                            <a v-if="paginate.current_page+2<=paginate.last_page"
                               @click="$emit('page',paginate.current_page+2)">{{paginate.current_page+2}}</a>
                            <a @click="$emit('page',paginate.last_page)">끝</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- //table-wrap -->
        <div class="container" v-if="list.length===0">
            <h2 class="xe-sr-only">데이터가 없는 경우</h2>
            <div class="xe-none-date">
                <i class="xi-error-o"></i>
                <p class="xe-none-date-text">
                    <span class="xe-none-date-text-title">검색 결과가 없습니다.</span>
                    검색어/제외검색의 입력이 정확한지 확인해 보세요.<br>
                    두 단어 이상의 검색어인 경우, 띄어쓰기를 확인해 보세요.<br>
                    검색 옵션을 다시 확인해 보세요.
                </p>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "OrderTable",
        props: [
            'list', 'paginate', 'asUrl', 'detailUrl', 'cancelUrl'
        ],
        computed: {
            currentCount() {
                if (this.paginate.total <= this.paginate.per_page) {
                    return this.paginate.total
                } else if (this.paginate.current_page === this.paginate.last_page) {
                    return this.paginate.total % this.paginate.per_page
                } else {
                    return this.paginate.per_page
                }
            }
        },
        methods: {
            url(url) {
                window.open(url, 'target=_blank' + new Date().getTime())
            },
            inDelivery (order) {
                return order.status ==='배송중' || order.status==='배송완료'
            },
            notInDelivery( order ){
                return order.status ==='상품준비' || order.status==='결제대기'
            },
            notInProcess (order) {
                return !this.notInDelivery( order ) &&  !this.inDelivery(order)
            }
        }
    }
</script>

<style scoped>
    .pagination a {
        color: #666;
        cursor: pointer;
    }

</style>
