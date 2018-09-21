<template>
    <div>
        <cart-list-component :cart-list="cartList" @checked="summary"></cart-list-component>
        <hr>
        <cart-sum-component :summary="cartSummary"></cart-sum-component>
        <hr>
        <div style="text-align: center" class="xe-col-lg-2 xe-col-lg-offset-4">
            <button class="xe-btn xe-btn-black xe-btn-lg xe-btn-block" type="submit">구매하기</button>
        </div>
        <div style="text-align: center" class="xe-col-lg-2">
            <button class="xe-btn xe-btn-lg xe-btn-block" type="button">쇼핑 계속하기</button>
        </div>
    </div>
</template>

<script>
    import CartListComponent from './Cart/CartListComponent'
    import CartSumComponent from './Cart/CartSumComponent'
  export default {
    name: "CartComponent",
    components: {
      CartListComponent, CartSumComponent
    },
    props: [
      'loadUrl', 'summaryUrl'
    ],
    data () {
      return {
        cartList: [],
        cartSummary: {
          original_price: 0,
          discount_price: 0,
          fare: 0,
          sum:0,
          millage: 0
        }
      }
    },
    methods: {
      load () {
        $.ajax({
          url: this.loadUrl
        }).done((res) => {
          this.cartList = res
        }).fail((err) => {
        })
      },
      summary (cart_ids) {
        $.ajax({
          url: this.summaryUrl,
          data: {
            cart_ids: cart_ids
          }
        }).done((res) => {
          console.log(res)
          console.log('sum')
          this.cartSummary = res
        }).fail((err) => {
          console.log(err)
        })
      }
    },
    mounted () {
      this.load()
      this.summary()
    }
  }
</script>

<style scoped>

</style>