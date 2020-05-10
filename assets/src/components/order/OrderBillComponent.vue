<template>
    <div class="payment-aside-top">
        <div class="payment-aside-title">
            <h1>최종 결제금액</h1>
        </div>

        <button type="button" class="btn-cart-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i></button>

        <div class="payment-aside-info">
            <div class="payment-aside-info-row">
                <h2 class="payment-aside-info-title">상품금액</h2>
                <div class="payment-aside-info-num">
                    {{Number(summary.original_price).toLocaleString()}}원
                </div>
            </div><!-- //payment-aside-info-row -->

            <div class="payment-aside-info-row">
                <h2 class="payment-aside-info-title">할인금액</h2>
                <div class="payment-aside-info-num">
                    <span class="payment-aside-icon"><i class="xi-minus-min"></i></span>
                    {{Number(summary.discount_price).toLocaleString()}}원
                </div>
            </div><!-- //payment-aside-info-row -->

            <!--<div class="payment-aside-info-row">-->
                <!--<h2 class="payment-aside-info-title">적립금 사용</h2>-->
                <!--<div class="payment-aside-info-num">-->
                    <!--<span class="payment-aside-icon"><i class="xi-minus-min"></i></span>-->
                    <!--0원-->
                <!--</div>-->
            <!--</div>&lt;!&ndash; //payment-aside-info-row &ndash;&gt;-->

            <div class="payment-aside-info-row">
                <h2 class="payment-aside-info-title">배송비</h2>
                <div class="payment-aside-info-num">
                    <span class="payment-aside-icon"><i class="xi-plus-min"></i></span>
                    {{Number(summary.fare).toLocaleString()}}원
                </div>
            </div><!-- //payment-aside-info-row -->

            <div class="payment-aside-info-sum">
                <h2 class="payment-aside-info-title">최종 결제금액</h2>
                <div class="payment-aside-info-num">
                    {{Number(summary.sum).toLocaleString()}}원
                </div>
            </div><!-- //payment-aside-info-row -->

            <button type="button" class="xe-btn payment-aside-btn" @click="pay">결제하기</button>

        </div><!-- //payment-aside-info -->
    </div><!-- //payment-aside-top -->
</template>

<script>
    export default {
        name: "OrderBillComponent",
        props: [
            'summary', 'payOption', 'validate', 'method', 'discountOption', 'orderId', 'user', 'userInfo', 'target'
        ],
        methods: {
            pay() {
                if (!this.validate.status) {
                    this.validate.msg.forEach(v=>{
                        XE.toast('danger',v)
                    })
                    return false
                }
                payment.submit({
                    method: this.method,
                    target: this.target,
                    user: {
                        email: this.user.email,
                        phone: this.userInfo.phone,
                        name: this.userInfo.name
                    },
                    _token: document.getElementById('csrf_token').value
                }, res => {
                    this.$emit('pay', res)
                }, err => {
                    if(typeof err !== 'undefined'){
                        alert('결제 실패!!' + err)
                    }
                })
            }
        }
    }
</script>

<style scoped>
    .card-content {
        padding: 10px;
    }

    .card-content tr {
        border-bottom: 1px #eee solid;
    }

    .card-content th, .card-content td {
        text-align: right;
        font-size: 13pt;
    }

    .card-content th {
        width: 30%;
        text-align: left;
    }
</style>
