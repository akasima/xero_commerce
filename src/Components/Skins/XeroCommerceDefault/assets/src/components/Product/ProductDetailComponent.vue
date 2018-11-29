<template>
    <div>
        <product-category-component
            v-for="(target, key) in product.categorys"
            :first="key===0"
            :categorys="category"
            :target-category="target"
            :key="key"
        ></product-category-component>
        <main>

            <!-- inner-main-->
            <div class="inner-main">
                <!-- view-header -->
                <div class="view-header">

                    <!-- view-slide -->
                    <div class="box-slide">
                        <span class="thumbnail" :style="{'background-image':'url('+mainImg+')'}"></span>
                        <div class="box_arrow reset-button">
                            <button type="button" class="btn-arrow btn-prev reset-button" @click="beforeMainImage"><i class="xi-angle-left"></i><span class="blind">왼쪽</span></button>
                            <button type="button" class="btn-arrow btn-next reset-button" @click="afterMainImage"><i class="xi-angle-right"></i><span class="blind">오른쪽</span></button>
                        </div>
                        <ul class="list-img reset-list">
                            <li v-for="(image, key) in product.images" class="item-img">
                                <a  @click.prevent="changeMainImage(key)" href="#" role="button" :style="{'background-image':'url('+image+')'}"></a>
                            </li>
                        </ul>
                    </div>
                    <!-- // view-slide -->

                    <!-- view-info-detail -->
                    <div class="view-info-detail">
                        <h2 class="title-view">{{product.data.name}}</h2>
                        <p class="text-summary">{{product.data.sub_name}}</p>
                        <div class="box-label">
                            <span v-for="label in product.labels" class="xe-shop-tag" :style="{background:label.background_color, color:label.text_color}">{{label.name}}</span>
                        </div>

                        <dl class="price">
                            <dt>판매가</dt>
                            <dd><span class="per">{{product.data.discount_percentage}}%</span> <span class="discount">{{Number(product.data.original_price).toLocaleString()}}<i>원</i></span></dd>
                        </dl>

                        <dl class="sale">
                            <dt>할인가</dt>
                            <dd>{{Number(product.data.sell_price).toLocaleString()}}<i>원</i></dd>
                        </dl>


                        <delivery-select-component
                            v-model="pay"
                            :delivery="product.delivery">
                        </delivery-select-component>

                        <option-select-component
                            v-model="choose"
                            :options="product.options"
                            :already-choose="[]">
                        </option-select-component>

                        <div class="box-button">
                            <a href="#" class="link-buy" @click="buyPage">구매하기</a>
                            <button type="button" class="btn-cart reset-button" @click="cartPage">장바구니</button>
                            <button type="button" class="btn-like reset-button" :class="(isWish)?'active':''" @click="toggleWish"><i class="xi-heart"></i> 찜하기</button>
                        </div>

                    </div>

                </div>
                <!-- // view-header -->

                <div class="area-view">
                    <ul class="tab-view reset-list">
                        <li class="item-view"><a href="#" @click.prevent="tab=1" :class="(tab===1) ? 'active' : ''" class="link-view active">상품정보</a></li>
                        <li class="item-view"><a href="#" @click.prevent="tab=2" :class="(tab===2) ? 'active' : ''" class="link-view">Q&A</a></li>
                        <li class="item-view"><a href="#" @click.prevent="tab=3" :class="(tab===3) ? 'active' : ''" class="link-view">상품후기</a></li>
                        <li class="item-view"><a href="#" @click.prevent="tab=4" :class="(tab===4) ? 'active' : ''" class="link-view">배송/교환/환불</a></li>
                    </ul>

                    <div class="view-content">
                        <!-- 에디터 영역 -->
                        <div v-if="tab===1" class="detail-information">
                            <h3 class="xe-sr-only">상품 상세 정보</h3>
                            <div class="detail-information-table">
                                <div class="detail-information-row" v-for="each in detail()">
                                    <template v-for="(val,key) in each">
                                        <div class="detail-information-cell th">{{key}}</div>
                                        <div class="detail-information-cell">{{val}}</div>
                                    </template>
                                </div>

                            </div>
                            <div class="detail-information-view">
                                <!-- [D] 상품 상세 정보 구역-->
                                <div v-html="product.data.description">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="detail-talks"></div> -->
                        <div v-if="tab===2" class="detail-qna">
                            <product-qna-component
                                :default-list="qnaList"
                                :product-id="product.id"
                                writer="test"
                                :qna-add-url="qnaAddUrl"
                                :qna-get-url="qnaGetUrl"
                                :answer-url="answerUrl"
                                :auth="auth"
                            ></product-qna-component>
                        </div>
                        <div v-if="tab===3" class="detail-qna">
                            <product-feed-back-component
                                :default-list="fbList"
                                :product-id="product.id"
                                writer="test"
                                :feedback-add-url="feedbackAddUrl"
                                :feedback-get-url="feedbackGetUrl"
                                :auth="auth"
                            ></product-feed-back-component>
                        </div>
                        <div v-if="tab===4" class="detail-as">
                            <div v-html="product.shop.as_info">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // inner-main-->
        </main>

        <form ref="form">
        </form>
    </div>
</template>

<script>
    import OptionSelectComponent from '../../../../../../../../assets/src/components/OptionSelectComponent'
    import DeliverySelectComponent from '../../../../../../../../assets/src/components/DeliverySelectComponent'
    import ProductCategoryComponent from './ProductCategoryComponent'
    import ProductQnaComponent from './ProductQnaComponent'
    import ProductFeedBackComponent from './ProductFeedBackComponent'

    export default {
        name: "ProductDetailComponent",
        components: {
            OptionSelectComponent, DeliverySelectComponent, ProductCategoryComponent, ProductQnaComponent, ProductFeedBackComponent
        },
        props: [
            'product', 'orderUrl', 'cartUrl', 'cartPageUrl', 'wishUrl', 'wishListUrl', 'category', 'qnaAddUrl', 'qnaGetUrl', 'qnaList', 'feedbackAddUrl', 'feedbackGetUrl', 'fbList', 'answerUrl', 'isWish', 'auth'
        ],
        computed: {
            mainImg() {
                return this.product.images[this.mainImageKey]
            },
            categoryMake() {
                return [];
            }
        },
        data() {
            return {
                choose: [],
                pay: '선불',
                tab: 1,
                mainImageKey: 0,
                benefit: false,
                endcodeUri: encodeURI(document.location.href)
            }
        },
        methods: {
            changeMainImage(key) {
                this.mainImageKey = key
            },
            beforeMainImage() {
                if (this.mainImageKey > 0) {
                    this.mainImageKey--
                } else {
                    this.mainImageKey = this.product.images.length - 1
                }
            },
            afterMainImage() {
                if (this.mainImageKey < this.product.images.length - 1) {
                    this.mainImageKey++
                } else {
                    this.mainImageKey = 0
                }
            },
            buyPage() {
                this.addCart(res => {
                    $.ajax({
                        url: this.orderUrl,
                        data: {
                            cart_id: [res.id],
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
                }, err => {
                    console.log(err)
                })
            },
            cartPage() {
                this.addCart(res => {
                    var conf = confirm('장바구니에 담았습니다. 장바구니로 갈까요?')
                    if (conf) {
                        document.location.href = this.cartPageUrl
                    }
                }, err => {
                    console.log(err)
                })
            },
            addCart(success, fail) {
                var val = this.validate()
                if (!val.status) {
                    alert(val.msg)
                    return
                }
                $.ajax({
                    url: this.cartUrl + '/' + this.product.id,
                    data: {
                        options: this.choose,
                        delivery: this.pay,
                        _token: document.getElementById('csrf_token').value
                    },
                    method: 'post'
                }).done(res => {
                    success(res)
                }).fail(err => {
                    fail(err)
                })
            },
            toggleWish() {
                if (this.auth) {
                    $.ajax({
                        url: this.wishUrl,
                        method: 'get'
                    }).done(res => {
                        if (Number(res)) {
                            var conf = confirm('관심상품에 담았습니다. 관심상품으로 갈까요?')
                            if (conf) {
                                document.location.href = this.wishListUrl
                            }
                            $(".wish-btn").addClass("active")
                        } else {
                            alert('관심상품에서 제거했습니다.')
                            $(".wish-btn").removeClass("active")
                        }
                    }).fail(err => {
                        console.log(err)
                    })
                }else{
                    XE.toast('warning','로그인 후 사용할 수 있습니다')
                }
            },
            detail() {
                var a = JSON.parse(this.product.data.detail_info)
                var keys = Object.keys(a)
                var unit = {
                    '상품정보': this.product.data.product_code
                }
                var result = []
                $.each(a, (k, v) => {
                    unit[k] = v
                    if (!(keys.indexOf(k) % 2) || keys.indexOf(k) + 1 === keys.length) {
                        result.push(JSON.parse(JSON.stringify(unit)));
                        unit = {}
                    }
                })
                return result
            },
            validate() {
                var validate =
                    {
                        status: true,
                        msg: ''
                    }

                if (this.choose.length === 0) {
                    validate.status = false,
                        validate.msg += '선택한 상품이 없습니다.'
                }
                if (Number(this.choose.map(v => {
                    return Number(v.count)
                }).reduce((a, b) => a + b, 0)) < 1) {
                    validate.status = false,
                        validate.msg += '선택한 상품의 갯수가 없습니다.'
                }
                if (this.product.data.min_buy_count) {
                    if (this.product.data.min_buy_count > Number(this.choose.map(v => {
                        return Number(v.count)
                    }).reduce((a, b) => a + b, 0))) {
                        validate.status = false,
                            validate.msg += '선택한 상품의 갯수가 최소 구매 수량보다 부족합니다.'
                    }
                }

                if (this.product.data.max_buy_count) {
                    if (this.product.data.max_buy_count < Number(this.choose.map(v => {
                        return Number(v.count)
                    }).reduce((a, b) => a + b, 0))) {
                        validate.status = false,
                            validate.msg += '선택한 상품의 갯수가 최대 구매 수량을 넘었습니다.'
                    }
                }
                return validate
            }
        }
    }
</script>

<style scoped>
    .highlight {
        border: 2px black solid;
    }
    .box-img {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 450px;
        height: 603px;
        background: #eee;
    }
    .product-sns-list {
    }
    .product-sns-list h3 a {
        color: #666;
    }
    .badge {
        z-index:1;
        background-color: #444;
        box-shadow: 0 0 3px 2px rgba(0,0,0,0.8);
        height: 100px;
        left: -50px;
        position: absolute;
        top: -50px;
        width: 100px;

        -webkit-transform: rotate(-45deg);
    }

    .badge span {
        color: #f5f5f5;
        font-family: sans-serif;
        font-size: 1.005em;
        left: 12px;
        top: 78px;
        position: absolute;
        width: 80px;
    }
</style>
