<template>
    <div>
        <h2 class="xe-sr-only">쇼핑 단계</h2>

        <div class="step-ui step-1">
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
        <cart-list-component :cart-list="cartList"
                             :cart-change-url="cartChangeUrl"
                             :cart-draw-url="cartDrawUrl"
                             :cart-draw-list-url="cartDrawListUrl"
                             @checked="summary"
                             @change="reload"
                             @only="onlyOrder"></cart-list-component>
        <cart-sum-component :summary="cartSummary" :disable="disable" @order="order" @back="back"></cart-sum-component>

        <form ref="form">
        </form>
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
            'cartList', 'summaryUrl', 'orderUrl', 'cartChangeUrl', 'cartDrawUrl', 'cartDrawListUrl'
        ],
        data() {
            return {
                cartSummary: {
                    original_price: 0,
                    discount_price: 0,
                    fare: 0,
                    sum: 0,
                    millage: 0
                },
                checkedList: [],
                disable: true
            }
        },
        methods: {
            summary(cart_ids) {
                this.disable = true
                this.checkedList = cart_ids
                $.ajax({
                    url: this.summaryUrl,
                    data: {
                        cart_ids: cart_ids
                    }
                }).done((res) => {
                    this.cartSummary = res
                    this.disable = false
                }).fail((err) => {
                    this.disable = false
                })
            },
            order() {
                var validate = this.validate()
                if(!validate.status){
                    alert(validate.msg)
                    return
                }
                $.ajax({
                    url: this.orderUrl,
                    data: {
                        cart_id: this.checkedList,
                        _token: document.getElementById('csrf_token').value
                    },
                    method: 'post'
                }).done((res) => {
                    var form = this.$refs.form;
                    form.setAttribute('action', res.url)
                    form.setAttribute('method', 'get')
                    var order_id = document.createElement('input')
                    order_id.setAttribute('type', 'hidden')
                    order_id.setAttribute('name', 'order_id')
                    order_id.setAttribute('value', res.order_id)
                    form.appendChild(order_id)
                    form.submit()
                })
            },
            onlyOrder(order_id) {
                this.checkedList = [order_id]
                this.order()
            },
            sum(array, key) {
                return array.map((v) => {
                    return v[key]
                }).reduce((a, b) => a + b, 0);
            },
            reload() {
                document.location.reload()
            },
            validate() {
                var validate = {
                    status: true,
                    msg: ''
                }
                if(this.cartList.filter((v)=>{return this.checkedList.indexOf(v.id) !== -1;}).map(v=>{return v.count;}).reduce((a,b)=>a+b,0)===0){
                    validate.status=false
                    validate.msg+= '구매하는 상품이 없거나 개수가 없니다.'
                }
                return validate
            },
            back () {
                window.history.back()
            }
        },
        mounted () {
            console.log(this.cartList)
        }
    }
</script>

<style scoped>

</style>
