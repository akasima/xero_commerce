<template>
    <div>
        <h1 class="page-title">주문/배송조회</h1>
        <order-table
            :list="[order]"
        :detail-url="detailUrl"
        :cancel-url="cancelUrl"></order-table>
        <section class="shipping-pay">
            <div class="table-wrap">
                <h4 class="table-type-title">결제금액정보</h4>
                <div class="table-type">
                    <div class="table-row">
                        <div class="table-cell header">
                            결제 금액
                        </div>
                        <div class="table-cell">
                            <b class="shipping-pay-total">{{order.payment.price.toLocaleString()}}원</b>
                            <div class="shipping-pay-history">
                                <div class="shipping-pay-row">
                                    <span class="shipping-pay-cell">상품금액</span> <span class="shipping-pay-cell">{{(order.payment.price-order.payment.fare+order.payment.discount).toLocaleString()}}원</span>
                                </div>

                                <!-- [D] 마이너스 금액은 클래스면  minus /  플러스 금액은 plus 클래스명 추가 부탁드립니다 -->
                                <div class="shipping-pay-row minus">
                                    <span class="shipping-pay-cell">할인금액</span> <span class="shipping-pay-cell">{{order.payment.discount.toLocaleString()}}원</span>
                                </div>

                                <!-- [D] 마이너스 금액은 클래스면  minus /  플러스 금액은 plus 클래스명 추가 부탁드립니다 -->
                                <!--<div class="shipping-pay-row minus">-->
                                    <!--<span class="shipping-pay-cell">적립금 사용</span> <span class="shipping-pay-cell">1,000원</span>-->
                                <!--</div>-->

                                <!-- [D] 마이너스 금액은 클래스면  minus /  플러스 금액은 plus 클래스명 추가 부탁드립니다 -->
                                <div class="shipping-pay-row plus">
                                    <span class="shipping-pay-cell">배송비</span> <span class="shipping-pay-cell">{{order.payment.fare.toLocaleString()}}원</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            결제 수단
                        </div>
                        <div class="table-cell">
                            <span class="shipping-pay-block">{{order.payment.method}}</span>
                            <span class="shipping-pay-block" :class="{bracket:i===0 || i===Object.keys(JSON.parse(order.payment.receipt)).length-1}" v-for="(v,k,i) in JSON.parse(order.payment.receipt)">{{v}}</span>
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            결제 완료일시
                        </div>
                        <div class="table-cell">
                            {{(order.payment.is_paid)?order.payment.updated_at:''}}
                        </div>
                    </div>
                </div><!-- //table-type -->
            </div><!-- //table-wrap -->
            <div class="table-wrap">
                <h4 class="table-type-title">주문 정보</h4>
                <div class="table-type">
                    <div class="table-row">
                        <div class="table-cell header">
                            이름
                        </div>
                        <div class="table-cell">
                            {{delivery.recv_name}}
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            연락처
                        </div>
                        <div class="table-cell">
                            {{delivery.recv_phone}}
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            주소
                        </div>
                        <div class="table-cell">
                            {{delivery.recv_addr + delivery.recv_addr_detail}}
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell header">
                            배송 메세지
                        </div>
                        <div class="table-cell">
                            {{delivery.recv_msg}}
                        </div>
                    </div>
                </div><!-- //table-type -->
            </div><!-- //table-wrap -->

        </section>
    </div>
</template>

<script>
    import OrderTable from './OrderTable'

    export default {
        name: "OrderDetailComponent",
        props: [
            'order', 'detailUrl', 'cancelUrl'
        ],
        components: {
            OrderTable
        },
        data() {
            return {
                delivery: {
                    recv_name: null,
                    recv_phone: null,
                    recv_addr: null,
                    recv_addr_detail: null,
                    recv_msg: null
                },
                paginate: {
                    current_page:1,
                    last_page:1,
                    first_page:1,
                    total:1
                }
            }
        },
        mounted() {
            this.delivery = this.order.orderItems[0].delivery
        }
    }
</script>

<style scoped>
    .table th {
        background: #f1f1f1;
        width: 200px;
    }
</style>
