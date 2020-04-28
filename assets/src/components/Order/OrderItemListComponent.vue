<template>
    <div class="cart-product">
        <div class="cart-product-header xe-hidden-xs xe-hidden-sm">
            <h3 class="xe-sr-only">목록</h3>
            <ul>
                <li>상품정보</li>
                <li>상품금액</li>
                <li>할인금액</li>
                <li>수량</li>
                <li>배송비</li>
                <li>주문금액</li>
            </ul>
        </div><!-- //cart-product-header -->
        <div class="cart-product-body" v-for="orderItem in orderItemList">
            <div class="cart-product-info">
                <div class="cart-product-img">
                    <img :src="orderItem.src" alt="상품이미지">
                </div><!-- //cart-product-img -->
                <div class="cart-product-name">
                    {{orderItem.name}}
                </div><!-- //cart-product-name -->
                <div class="cart-product-option">
                    <ul class="cart-product-option-list">
                        <li v-for="option in orderItem.options">
                            <span>
                              {{option.unit.name}}
                              <span v-for="(value, key, i) in option.custom_values">
                                {{ i == 0 ? '(' : '' }}
                                {{key}} : {{value}}
                                {{ i != Object.keys(option.custom_values).length - 1 ? ',' : ')' }}
                              </span>
                              / {{option.count}}개
                            </span>
                        </li>
                    </ul>
                </div><!-- //cart-product-option -->
            </div><!-- //cart-product-info -->
            <button type="button" class="btn-cart-num-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i><span class="xe-sr-only">금액 정보 토글</span></button>
            <div class="cart-product-num">
                <div class="cart-product-num-box">
                    <div class="cart-product-price">
                        <span class="cart-product-title">상품금액</span>
                        <span class="cart-product-text">{{orderItem.original_price.toLocaleString()}}원</span>
                    </div><!-- //cart-product-price -->
                    <div class="cart-product-sale">
                        <span class="cart-product-title">할인금액</span>
                        <span class="cart-product-text">{{orderItem.discount_price.toLocaleString()}}원</span>
                    </div><!-- //cart-product-sale -->
                    <div class="cart-product-quantity">
                        <span class="cart-product-title">수량</span>
                        <span class="cart-product-text">{{orderItem.count}}개</span>
                    </div><!-- //cart-product-num -->
                    <div class="cart-product-shipping">
                        <span class="cart-product-title">배송비</span>
                        <span class="cart-product-text">{{orderItem.fare.toLocaleString()}}원</span>
                    </div><!-- //cart-product-shipping -->
                    <div class="cart-product-sum">
                        <span class="cart-product-title">주문 금액</span>
                        <span class="cart-product-text">{{orderItem.sell_price.toLocaleString()}}원</span>
                    </div><!-- //cart-product-sum -->
                </div><!-- //cart-product-num-box -->
            </div><!-- //cart-product-num -->

        </div><!-- //cart-product-body -->

    </div> <!-- //cart-product -->
</template>

<script>
  export default {
    name: "OrderItemListComponent",
    props: [
      "orderItemList"
    ],
    data () {
      return {

      }
    }
  }
</script>

<style scoped>

</style>
