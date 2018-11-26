<template>
    <div class="xe-shop cart">
        <div class="container">
            <div class="cart-wrap">
                <button type="button" class="btn-cart-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i></button>
                <div class="cart-product">
                    <div class="cart-product-header xe-hidden-xs xe-hidden-sm">
                        <h3 class="xe-sr-only">목록</h3>
                        <ul>
                            <li>
                                <label class="xe-label">
                                    <input type="checkbox" v-model="allCheck">
                                    <span class="xe-input-helper"></span>
                                    <span class="xe-label-text xe-sr-only">체크 박스</span>
                                </label>
                            </li>
                            <li>상품정보</li>
                            <li>상품금액</li>
                            <li>할인금액</li>
                            <li>수량</li>
                            <li>배송비</li>
                            <li>주문금액</li>
                        </ul>
                    </div><!-- //cart-product-header -->
                    <div class="cart-product-body" v-for="cartItem in cartList">
                        <div class="cart-product-checkbox">
                            <label class="xe-label">
                                <input type="checkbox" v-model="checkedList" :value="cartItem.id">
                                <span class="xe-input-helper"></span>
                                <span class="xe-label-text xe-sr-only">체크박스</span>
                            </label>
                        </div><!-- //cart-product-checkbox -->
                        <div class="cart-product-info">
                            <div class="cart-product-img">
                                <img :src="cartItem.src" alt="상품이미지">
                            </div><!-- //cart-product-img -->
                            <div class="cart-product-name">
                                <a href="#" @click.prevent="url(cartItem.url)">{{cartItem.name}}</a>
                            </div><!-- //cart-product-name -->
                            <div class="cart-product-option">
                                <button type="button" class="xe-btn xe-btn-secondary" @click="changeModal(cartItem)">옵션변경</button>
                                <div class="modal" :id="'cartChangeModal'+cartItem.id">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="padding:20px">
                                            <div class="modal-header">
                                                <h2>주문 추가/변경</h2>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <img :src="cartItem.src" alt="" width="100" height="100">
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <h3>{{cartItem.name}}</h3>
                                                        <h3>{{cartItem.sell_price.toLocaleString()}} 원</h3>
                                                    </div>
                                                </div>
                                                <option-select-component
                                                    :delivery="cartItem.delivery"
                                                    v-model="cartItem.choose"
                                                    :already-choose="cartItem.choose"
                                                    :options="cartItem.option_list"></option-select-component>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="xe-btn xe-btn-black" @click="edit(cartItem)">저장하고 계속</button>
                                                <button class="xe-btn xe-btn-danger" data-dismiss="xe-modal">저장하지않고 닫기</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="cart-product-option-list">
                                    <li v-for="choose in cartItem.choose">
                                        <span>{{choose.unit.name}} / {{choose.count}}개 </span>
                                    </li>
                                </ul>
                            </div><!-- //cart-product-option -->
                        </div><!-- //cart-product-info -->
                        <button type="button" class="btn-cart-num-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i><span class="xe-sr-only">금액 정보 토글</span></button>
                        <div class="cart-product-num">
                            <div class="cart-product-num-box">
                                <div class="cart-product-price">
                                    <span class="cart-product-title">상품금액</span>
                                    <span class="cart-product-text">{{cartItem.original_price.toLocaleString()}}원</span>
                                </div><!-- //cart-product-price -->
                                <div class="cart-product-sale">
                                    <span class="cart-product-text"><i class="xi-minus-min"></i>{{cartItem.discount_price.toLocaleString()}}원</span>
                                </div><!-- //cart-product-sale -->
                                <div class="cart-product-quantity">
                                    <span class="cart-product-title">수량</span>
                                    <span class="cart-product-text">{{cartItem.count}}개</span>
                                </div><!-- //cart-product-num -->
                                <div class="cart-product-shipping">
                                    <span class="cart-product-title">배송비</span>
                                    <span class="cart-product-text"><i class="xi-plus-min"></i>({{cartItem.fare.toLocaleString()}}원)</span>
                                </div><!-- //cart-product-shipping -->
                                <div class="cart-product-sum">
                                    <span class="cart-product-title">주문 금액</span>
                                    <span class="cart-product-text">{{cartItem.sell_price.toLocaleString()}}원</span>
                                    <div class="cart-product-btn">
                                        <button type="button" class="btn-buynow" @click="onlyThisCart(cartItem.id)">주문하기</button>
                                        <button type="button" class="btn-delete" @click="draw(cartItem.id)">삭제하기</button>
                                    </div><!-- //cart-product-btn -->
                                </div><!-- //cart-product-sum -->
                            </div><!-- //cart-product-num-box -->
                        </div><!-- //cart-product-num -->

                    </div><!-- //cart-product-body -->

                </div> <!-- //cart-product -->

                <div class="bottom-btn-box">
                    <button type="button" class="btn-default" @click="drawList">선택상품 삭제</button>
                    <button type="button" class="btn-default">선택상품 찜</button>
                </div><!-- //cart-product-list-btn -->

            </div><!-- //cart-wrap  -->
        </div><!-- //container  -->
    </div><!-- //cart -->
</template>

<script>
    import OptionSelectComponent from '../OptionSelectComponent'
    import DeliverySelectComponent from '../DeliverySelectComponent'

    export default {
        components: {
            OptionSelectComponent, DeliverySelectComponent
        },
        props: [
            'cartList', 'cartChangeUrl', 'cartDrawUrl', 'cartDrawListUrl'
        ],
        watch: {
            cartList() {
                this.checkedList = this.cartList.map((v) => {
                    return v.id
                });
            },
            checkedList(el) {
                this.$emit('checked', el);
            },
            allCheck(el) {
                if (el) {
                    this.checkedList = this.cartList.map((v) => {
                        return v.id
                    });
                } else {
                    this.checkedList = [];
                }
            }
        },
        name: "CartListComponent",
        data() {
            return {
                checkedList: [],
                allCheck: false
            }
        },
        methods: {
            changeModal(cart) {
                $('#cartChangeModal' + cart.id).xeModal()
            },
            edit(cart) {
                var val= this.validate(cart)
                if(! val.status){
                    alert(val.msg)
                    return
                }
                $.ajax({
                    url: this.cartChangeUrl + '/' + cart.id,
                    data: cart
                }).done(() => {
                    this.$emit('change')
                }).fail(() => {
                    console.log('fail')
                })
            },
            validate (cart) {
                var validate =
                    {
                        status: true,
                        msg: ''
                    }
                if(cart.max)
                {
                    if(Number(cart.choose.map(v=>{return Number(v.count)}).reduce((a,b)=>a+b,0))>cart.max)
                    {
                        validate.status = false,
                            validate.msg+='선택한 상품의 갯수가 최대 구매 수량을 넘었습니다.'
                    }
                }
                if(cart.min)
                {
                    if(Number(cart.choose.map(v=>{return Number(v.count)}).reduce((a,b)=>a+b,0))<cart.min)
                    {
                        validate.status = false,
                            validate.msg+='선택한 상품의 갯수가 최소 구매 수량보다 부족합니다.'
                    }
                }
                return validate;
            },
            draw(cart_id) {
                $.ajax({
                    url: this.cartDrawUrl + '/' + cart_id
                }).done(() => {
                    this.$emit('change')
                }).fail(() => {
                    console.log('fail')
                })
            },
            drawList() {
                var confirm = window.confirm(this.checkedList.length + ' 개 장바구니를 삭제합니다. 계속 하시겠습니까?')
                if(confirm){
                    $.ajax({
                        url: this.cartDrawListUrl,
                        data: {
                            cart_id:this.checkedList
                        }
                    }).done(() => {
                        this.$emit('change')
                    }).fail(() => {
                        console.log('fail')
                    })
                }
            },
            wishList() {

            },
            onlyThisCart(cart_id) {
                this.$emit('only', cart_id)
            },
            url(url) {
                window.open(url, 'target=_blank' + new Date().toTimeString())
            }
        },
        mounted() {
            this.allCheck = true
        }
    }
</script>

<style scoped>

</style>
