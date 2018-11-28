<template>
    <div class="xe-shop cart-number">
        <div class="xero-container">
            <section class="cart-number-wrap">
                <h2 class="xe-sr-only">장바구니 결제 정보</h2>
                <article class="cart-number-price">
                    <h3 class="cart-number-title">상품금액</h3>
                    <div class="cart-number-text">{{Number(summary.original_price).toLocaleString()}}원</div>
                </article>
                <article class="cart-number-sale">
                    <h3 class="cart-number-title">할인금액</h3>
                    <div class="cart-number-text">
                        <span class="cart-number-icon"><i class="xi-minus-min"></i></span>
                        {{Number(summary.discount_price).toLocaleString()}}원
                    </div>
                </article>
                <article class="cart-number-shipping">
                    <h3 class="cart-number-title">배송금액</h3>
                    <div class="cart-number-text">
                        <span class="cart-number-icon"><i class="xi-plus-min"></i></span>
                        {{Number(summary.fare).toLocaleString()}}원
                    </div>
                </article>
                <article class="cart-number-sum">
                    <h3 class="cart-number-title">결제 예정 금액</h3>
                    <div class="cart-number-text">{{Number(summary.sum).toLocaleString()}}원</div>
                    <div class="cart-number-caption">적립금 혜택 <span>{{Number(millage).toLocaleString()}}</span>원</div>
                </article>

            </section> <!-- //cart-number-wrap -->

            <section class="page-btn">
                <h2 class="xe-sr-only">장바구니 결제 버튼</h2>
                <button type="submit" class="page-btn-black" @click="order" :disabled="disable">구매하기</button>
                <button type="submit" class="page-btn-white" @click="back">쇼핑 계속하기</button>
            </section><!-- //page-btn-box -->

        </div> <!-- //container -->
    </div> <!-- //cart-number -->
</template>

<script>
    export default {
        name: "CartSumComponent",
        props: [
            'summary', 'disable'
        ],
        data() {
            return {
                millage: 0
            }
        },
        methods: {
            order () {
                this.$emit('order')
            },
            back () {
                this.$emit('back')
            }
        }
    }
</script>

<style scoped>

</style>
