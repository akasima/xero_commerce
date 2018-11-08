<template>
    <div>
        <product-category-component
            :categorys="category"
            :target-category="product.categorys"
        ></product-category-component>
        <div class="xe-shop product">
            <div class="container">
                <div class="product-wrap">
                    <h2 class="xe-sr-only">상품 관련 정보</h2>
                    <div class="product-img">
                        <h3 class="xe-sr-only">상품 이미지</h3>
                        <div class="product-img-view">
                            <img :src="mainImg" style="width:450px">
                            <button class="xe-btn left" @click="beforeMainImage"><i class="xi-angle-left-thin"><span class="xe-sr-only">이전 사진 보기</span></i></button>
                            <button class="xe-btn right" @click="afterMainImage"><i class="xi-angle-right-thin"><span class="xe-sr-only">다음 사진 보기</span></i></button>
                        </div>
                        <div class="product-img-list">
                            <h3 class="xe-sr-only">상품 이미지 목록</h3>
                            <ul>
                                <li v-for="(image, key) in product.images"><a href="#" @click.prevent="changeMainImage(key)"><img :src="image" alt=""></a></li>
                            </ul>
                        </div>
                    </div><!-- //product-img  -->
                    <div class="product-info">
                        <div class="product-info-top">
                            <h3 class="product-info-title">{{product.data.name}}</h3>
                            <div class="label_wrap">
                                <span v-for="label in product.labels" class="xe-shop-tag" :style="{background:label.background_color, color:label.text_color}">{{label.name}}</span>
                            </div>
                            <p class="product-info-title-caption">{{product.data.sub_name}}</p>
                        </div>
                        <div class="product-info-container">
                            <div class="product-info-cell price"> <span>{{Number(product.data.sell_price).toLocaleString()}}원</span></div>
                            <div class="product-info-cell before-price">{{Number(product.data.original_price).toLocaleString()}}<span>원</span></div>
                            <div class="product-info-favor">
                                <button type="button" class="favor-btn"> 구매 혜택 보기</button>

                                <!-- [D] 클릭시 on 클래스 추가 부탁드립니다. -->
                                <div class="xe-tooltip bottom " role="tooltip">
                                    <div class="xe-tooltip-arrow"></div>
                                    <div class="xe-tooltip-inner">
                                        <h4 class="favor-title">구매혜택</h4>
                                        <div>
                                            <p class="favor-text">추가 적립 포인트 최대 1,000원</p>
                                        </div>
                                        <div>
                                            <p class="favor-text">구매평 작성시 포인트 최대 500원</p>
                                        </div>
                                        <h4 class="favor-title">무이자할부</h4>
                                        <div>
                                            <p class="favor-text">KB국민카드</p>
                                            <p class="favor-text right">2,3,4,5개월 무이자 (5만원 이상)</p>
                                        </div>
                                        <div>
                                            <p class="favor-text">삼성카드</p>
                                            <p class="favor-text right">2,3,4,5개월 무이자 (5만원 이상)</p>
                                        </div>
                                        <button type="button" class="btn-tooltip-close"><i class="xi-close-thin"></i><span class="xe-sr-only">닫기</span></button>
                                    </div>
                                </div>
                            </div><!-- //product-info-favor  -->
                            <div class="product-info-cell shipping">배송비 2,500원(주문시 결제)</div>

                            <div class="btn-buy-wrap xe-hidden-md xe-hidden-lg">
                                <button class="xe-btn xe-btn-buy" @click="buyPage">구매하기</button>
                                <button class="xe-btn" @click="cartPage"><i class="xe-visible-xs xe-visible-sm xi-basket"></i><span class="xe-visible-md xe-visible-lg">장바구니</span></button>
                                <button class="xe-btn" @click="toggleWish"><i class="xe-visible-xs xe-visible-sm xi-heart"></i><span class="xe-visible-md xe-visible-lg">찜하기</span></button>
                            </div><!-- //btn-buy-wrap -->
                        </div>

                        <!-- [D] 모바일에서 활성화시 on 클래스 추가 부탁드립니다 -->
                        <div class="product-info-options on" id="toggleBtn">
                            <option-select-component
                                v-model="choose"
                                :options="product.options"
                                :already-choose="[]">
                                <delivery-select-component
                                    v-model="pay"
                                    :delivery="product.delivery">
                                </delivery-select-component>
                            </option-select-component>
                            <div class="btn-buy-wrap">
                                <button class="xe-btn xe-btn-buy" @click="buyPage" >구매하기</button>
                                <button class="xe-btn" @click="cartPage"><i class="xe-visible-xs xe-visible-sm xi-basket"></i><span class="xe-visible-md xe-visible-lg">장바구니</span></button>
                                <button class="xe-btn" @click="toggleWish"><i class="xe-visible-xs xe-visible-sm xi-heart"></i><span class="xe-visible-md xe-visible-lg">찜하기</span></button>
                            </div><!-- //btn-buy-wrap -->

                        </div><!-- //options -->
                    </div><!-- //product-info  -->
                </div><!-- //product-wrap  -->
            </div><!-- //container  -->
        </div><!-- //product -->
        <div class="xe-shop detail">
            <div class="container">
                <div class="detail-wrap">
                    <h2 class="xe-sr-only">상품 상세 보기</h2>
                    <div class="detail-tab">
                        <h3 class="xe-sr-only">상품 정보 탭</h3>
                        <ul>
                            <li :class="(tab===1) ? 'active' : ''"><a href="#" @click.prevent="tab=1">상품정보</a></li>
                            <!--<li><a href="#">구매평</a></li>-->
                            <li :class="(tab===3) ? 'active' : ''"><a href="#" @click.prevent="tab=3">Q&A</a></li>
                            <li :class="(tab===2) ? 'active' : ''"><a href="#" @click.prevent="tab=2">반품정보</a></li>
                        </ul>
                    </div>
                    <div class="detail-container">
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
                         <div v-if="tab===3" class="detail-qna">
                             <table class="table">
                             <thead>
                             <tr>
                             <th>번호</th>
                             <th>제목</th>
                             <th>작성자</th>
                             <th>작성일</th>
                             </tr>
                             </thead>
                             <tbody>
                             <tr>
                             <td>1</td>
                             <td>??</td>
                             <td>hero</td>
                             <td>{{new Date()}}</td>
                             </tr>
                             <tr>
                             <td colspan="4">dafdfdsafdf <br>dsafdfd</td>
                             </tr>
                             <tr>
                             <td>1</td>
                             <td>??</td>
                             <td>hero</td>
                             <td>{{new Date()}}</td>
                             </tr>
                             <tr>
                             <td colspan="4">dafdfdsafdf</td>
                             </tr>
                             </tbody>
                             </table>
                         </div>
                         <div v-if="tab===2" class="detail-as">
                             <div v-html="product.shop.as_info">

                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="row">-->
            <!--<div class="col">-->
                <!--<div>-->
                    <!--<b-tabs>-->
                        <!--<b-tab title="상품정보" active>-->
                            <!--<h4>상품 상세 정보</h4>-->
                            <!--<table class="xe-table">-->
                                <!--<tr v-for="each in detail()">-->
                                    <!--<template v-for="(val,key) in each">-->
                                        <!--<th>-->
                                            <!--{{key}}-->
                                        <!--</th>-->
                                        <!--<td>-->
                                            <!--{{val}}-->
                                        <!--</td>-->
                                    <!--</template>-->
                                <!--</tr>-->
                            <!--</table>-->
                            <!--<div v-html="product.data.description">-->
                            <!--</div>-->
                        <!--</b-tab>-->
                        <!--<b-tab title="구매평">-->
                        <!--<h4>구매평</h4>-->
                            <!--<b-list-group style="margin-top:10px">-->
                                <!--<b-list-group-item href="#" class="flex-column align-items-start">-->
                                    <!--<div class="row">-->
                                        <!--<div class="col-1">-->
                                            <!--<img :src="mainImg" alt="" style="width:70px;height:70px">-->
                                        <!--</div>-->
                                        <!--<div class="col">-->
                                            <!--<div class="d-flex w-100 justify-content-between">-->
                                                <!--<h5 class="mb-1">List group item heading</h5>-->
                                                <!--<small>3 days ago</small>-->
                                            <!--</div>-->
                                            <!--<p class="mb-1">-->
                                                <!--Donec id elit non mi porta gravida at eget metus. Maecenas-->
                                                <!--sed diam eget risus varius blandit.-->
                                            <!--</p>-->
                                            <!--<small>Donec id elit non mi porta.</small>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                <!--</b-list-group-item>-->
                                <!--<b-list-group-item href="#" class="flex-column align-items-start">-->
                                    <!--<div class="d-flex w-100 justify-content-between">-->
                                        <!--<h5 class="mb-1">List group item heading</h5>-->
                                        <!--<small class="text-muted">3 days ago</small>-->
                                    <!--</div>-->
                                    <!--<p class="mb-1">-->
                                        <!--Donec id elit non mi porta gravida at eget metus. Maecenas-->
                                        <!--sed diam eget risus varius blandit.-->
                                    <!--</p>-->
                                    <!--<small class="text-muted">Donec id elit non mi porta.</small>-->
                                <!--</b-list-group-item>-->
                                <!--<b-list-group-item href="#" disabled class="flex-column align-items-start">-->
                                    <!--<div class="d-flex w-100 justify-content-between">-->
                                        <!--<h5 class="mb-1">Disabled List group item</h5>-->
                                        <!--<small class="text-muted">3 days ago</small>-->
                                    <!--</div>-->
                                    <!--<p class="mb-1">-->
                                        <!--Donec id elit non mi porta gravida at eget metus. Maecenas-->
                                        <!--sed diam eget risus varius blandit.-->
                                    <!--</p>-->
                                    <!--<small class="text-muted">Donec id elit non mi porta.</small>-->
                                <!--</b-list-group-item>-->
                            <!--</b-list-group>-->
                        <!--</b-tab>-->
                        <!--<b-tab title="Q&A">-->
                        <!--<h4>Q%A</h4>-->
                            <!--<table class="table">-->
                                <!--<thead>-->
                                <!--<tr>-->
                                    <!--<th>번호</th>-->
                                    <!--<th>제목</th>-->
                                    <!--<th>작성자</th>-->
                                    <!--<th>작성일</th>-->
                                <!--</tr>-->
                                <!--</thead>-->
                                <!--<tbody>-->
                                <!--<tr>-->
                                    <!--<td>1</td>-->
                                    <!--<td>??</td>-->
                                    <!--<td>hero</td>-->
                                    <!--<td>{{new Date()}}</td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                    <!--<td colspan="4">dafdfdsafdf <br>dsafdfd</td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                    <!--<td>1</td>-->
                                    <!--<td>??</td>-->
                                    <!--<td>hero</td>-->
                                    <!--<td>{{new Date()}}</td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                    <!--<td colspan="4">dafdfdsafdf</td>-->
                                <!--</tr>-->
                                <!--</tbody>-->
                            <!--</table>-->
                        <!--</b-tab>-->
                        <!--<b-tab title="반품정보">-->
                            <!--<h4>반품정보</h4>-->
                            <!--<div v-html="product.shop.as_info">-->

                            <!--</div>-->
                        <!--</b-tab>-->
                    <!--</b-tabs>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
        <form ref="form">
        </form>
    </div>
</template>

<script>
    import OptionSelectComponent from '../../../../../../../../assets/src/components/OptionSelectComponent'
    import DeliverySelectComponent from '../../../../../../../../assets/src/components/DeliverySelectComponent'
    import ProductCategoryComponent from './ProductCategoryComponent'

    export default {
        name: "ProductDetailComponent",
        components: {
            OptionSelectComponent, DeliverySelectComponent, ProductCategoryComponent
        },
        props: [
            'product', 'orderUrl', 'cartUrl', 'cartPageUrl', 'wishUrl', 'wishListUrl', 'category'
        ],
        computed:{
            mainImg () {
                return this.product.images[this.mainImageKey]
            },
            categoryMake(){
                return [];
            }
        },
        data() {
            return {
                choose: [],
                pay: '선불',
                tab: 1,
                mainImageKey: 0
            }
        },
        methods: {
            changeMainImage(key) {
                this.mainImageKey = key
            },
            beforeMainImage() {
                if(this.mainImageKey>0){
                    this.mainImageKey--
                }
            },
            afterMainImage() {
                if(this.mainImageKey<this.product.images.length-1){
                    this.mainImageKey++
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
                var val= this.validate()
                if(! val.status){
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
            toggleWish(){
                $.ajax({
                    url: this.wishUrl,
                    method: 'get'
                }).done(res => {
                    if(Number(res)){
                        var conf = confirm('관심상품에 담았습니다. 관심상품으로 갈까요?')
                        if (conf) {
                            document.location.href = this.wishListUrl
                        }
                    }else{
                        alert('관심상품에서 제거했습니다.')
                    }
                }).fail(err => {
                    console.log(err)
                })
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

                if(this.choose.length === 0){
                    validate.status = false,
                        validate.msg+='선택한 상품이 없습니다.'
                }
                if(Number(this.choose.map(v=>{return Number(v.count)}).reduce((a,b)=>a+b,0))<1)
                {
                    validate.status = false,
                        validate.msg+='선택한 상품의 갯수가 없습니다.'
                }
                if(this.product.data.min_buy_count){
                    if(this.product.data.min_buy_count > Number(this.choose.map(v=>{return Number(v.count)}).reduce((a,b)=>a+b,0)))
                    {
                        validate.status = false,
                            validate.msg+='선택한 상품의 갯수가 최소 구매 수량보다 부족합니다.'
                    }
                }

                if(this.product.data.max_buy_count){
                    if(this.product.data.max_buy_count < Number(this.choose.map(v=>{return Number(v.count)}).reduce((a,b)=>a+b,0)))
                    {
                        validate.status = false,
                            validate.msg+='선택한 상품의 갯수가 최대 구매 수량을 넘었습니다.'
                    }
                }
                return validate
            }
        }
    }
</script>

<style scoped>

</style>
