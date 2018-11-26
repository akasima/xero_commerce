<template>
    <div>
        <h2 class="xe-sr-only">쇼핑 단계</h2>

        <div class="step-ui step-2">
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
        <order-item-list-component :order-item-list="orderItemList"></order-item-list-component>
        <div class="payment-bottom">
            <div class="table-wrap">
                <h4 class="table-type-title">주문고객 정보</h4>
                <button type="button" class="btn-cart-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i></button>
                <div class="table-type">
                    <div class="table-row">
                        <div class="table-cell header">
                            이름
                        </div>
                        <div class="table-cell">
                            {{userInfo.name}}
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            연락처
                        </div>
                        <div class="table-cell">
                            {{userInfo.phone}}
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            이메일
                        </div>
                        <div class="table-cell">
                            {{user.email}}
                        </div>
                    </div>
                </div><!-- //table-type -->
            </div><!-- //table-wrap -->
            <order-delivery-component :user-info="userInfo" v-model="delivery"></order-delivery-component>
            <!--<div class="table-wrap">-->
                <!--<h4 class="table-type-title">할인 정보</h4>-->
                <!--<button type="button" class="btn-cart-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i></button>-->
                <!--<div class="table-type">-->
                    <!--<div class="table-row">-->
                        <!--<div class="table-cell header">-->
                            <!--할인 쿠폰-->
                        <!--</div>-->
                        <!--<div class="table-cell">-->
                            <!--<input type="text" class="xe-form-control table-input table-input-sale" value="1,000">-->
                            <!--원-->
                            <!--<button type="button" class="xe-btn xe-btn-secondary table-btn-sale">쿠폰사용</button>-->
                            <!--<span class="xe-hidden-xs xe-hidden-sm">(사용가능쿠폰 1장 / 보유쿠폰 2장)</span>-->
                        <!--</div>-->
                    <!--</div>-->

                    <!--<div class="table-row">-->
                        <!--<div class="table-cell header">-->
                            <!--적립금 사용-->
                        <!--</div>-->
                        <!--<div class="table-cell">-->
                            <!--<input type="text" class="xe-form-control table-input table-input-sale" value="1,000">-->
                            <!--원-->
                            <!--<button type="button" class="xe-btn xe-btn-secondary table-btn-sale">쿠폰사용</button>-->
                            <!--<span class="xe-hidden-xs xe-hidden-sm">(사용가능적립금 : 10,000원)</span>-->
                        <!--</div>-->
                    <!--</div>-->

                <!--</div>&lt;!&ndash; //table-type &ndash;&gt;-->
            <!--</div>&lt;!&ndash; //table-wrap &ndash;&gt;-->
            <pay-component :pay-methods="payMethods" v-model="payMethod"></pay-component>
            <aside class="payment-aside">
                <order-bill-component :summary="orderSummary"
                                      :validate="validate"
                                      :method="payMethod"
                                      :order-id="order_id"
                                      @pay="register"
                                      :discount-option="discountOption"
                                      :user-info="userInfo"
                                      :user="user"
                                      :target="payTarget"
                ></order-bill-component>
                <order-agreement-component :agreements="agreements" v-model="agreed" :agree-url="agreeUrl"
                                           :denied-url="deniedUrl"></order-agreement-component>
            </aside>
        </div>
    </div>
</template>

<script>
    import OrderItemListComponent from './OrderItemListComponent'
    import OrderDeliveryComponent from './OrderDeliveryComponent'
    import OrderBillComponent from './OrderBillComponent'
    import OrderAgreementComponent from './OrderAgreementComponent'
    import PayComponent from './PayComponent'

    export default {
        name: "OrderRegisterComponent",
        components: {
            OrderDeliveryComponent, OrderBillComponent, OrderAgreementComponent, OrderItemListComponent, PayComponent
        },
        props: [
            'orderItemList', 'orderSummary', 'user', 'userInfo', 'order_id', 'dashUrl', 'successUrl', 'failUrl', 'agreements', 'payMethods', 'agreeUrl', 'deniedUrl', 'payTarget'
        ],
        computed: {
            validate() {
                var res = {
                    status: true,
                    msg: []
                }
                if (this.agreed.length < 3) {
                    res.msg.push('필수 약관에 모두 동의후 결제가 진행됩니다.')
                    res.status = false
                }
                if (this.delivery === null) {
                    res.msg.push('주소가 불분명합니다.')
                    res.status = false
                } else {
                    if (this.delivery.addr === '') {
                        res.msg.push('주소가 불분명합니다.')
                        res.status = false
                    }
                    if (this.delivery.addr_detail === '') {
                        res.msg.push('상세주소를 적어주세요.')
                        res.status = false
                    }
                }
                if(this.payMethod === null){
                    res.msg.push('결제방식을 선택해주세요.')
                    res.status = false
                }
                return res
            }
        },
        data() {
            return {
                delivery: null,
                payMethod: null,
                easyMethodList: [],
                agreed: [],
                discountOption: false
            }
        },
        methods: {
            register(pay) {
                if (pay.status) {
                    $.ajax({
                        url: this.successUrl,
                        method: 'post',
                        data: {
                            delivery: this.delivery,
                            _token: document.getElementById('csrf_token').value
                        }
                    }).done(this.complete).fail(this.fail())
                } else {
                    document.location.href = this.failUrl
                }
            },
            complete(res) {
                document.location.href = this.dashUrl
            },
            fail(error) {
                console.log(error)
                //document.location.href = this.failUrl
            }
        },
        mounted() {
            console.log(this.payMethods)
        }
    }
</script>

<style scoped>
    .card-content table {
        margin: 0
    }
</style>
