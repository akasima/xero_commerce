<template>
    <div>
        <cart-list-component :cart-list="cartList" @checked="summary"></cart-list-component>
        <hr>
        <cart-sum-component :summary="cartSummary"></cart-sum-component>
        <hr>
        <div style="text-align: center" class="xe-col-lg-2 xe-col-lg-offset-4">
            <button class="xe-btn xe-btn-black xe-btn-lg xe-btn-block" @click="order">구매하기</button>
        </div>
        <div style="text-align: center" class="xe-col-lg-2">
            <button class="xe-btn xe-btn-lg xe-btn-block" type="button">쇼핑 계속하기</button>
        </div>
    </div>
</template>

<script>
    import CartListComponent from './CartListComponent'
    import CartSumComponent from './CartSumComponent'
  export default {
    name: "CartComponent",
    components: {
      CartListComponent, CartSumComponent
    },
    props: [
      'loadUrl', 'summaryUrl', 'orderUrl'
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
        },
        checkedList: []
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
        this.checkedList = cart_ids
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
      },
      order () {
        $.ajax({
          url: this.orderUrl,
          data: {
            cart_id: this.checkedList,
            _token: document.getElementById('csrf_token').value
          },
          method: 'post'
        }).done((res) => {
          document.location.href = res.url;
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