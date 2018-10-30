<template>
    <div>
        <div class="row" style="margin-top: 20px">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col" >
                        <div style="margin:0 auto; width:400px;">
                            <img :src="mainImg">
                        </div>
                    </div>
                </div>
                <div class="row" v-if="product.images.length >1">
                    <div class="col" style="overflow-x:scroll;height:60px; width:100%">
                        <img v-for="image in product.images" :src="image" style="width:50px; height:40px"
                             @click="changeMainImage(image)">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col">
                        <h1>{{product.data.name}}</h1>
                        <p>{{product.data.sub_name}}</p>
                        <span style="text-decoration-line: line-through;color: #aaa">{{Number(product.data.original_price).toLocaleString()}} 원</span>
                        <h2>{{Number(product.data.sell_price).toLocaleString()}} 원</h2>
                        <option-select-component
                            v-model="choose"
                            :options="product.options"
                            :already-choose="[]">
                            <delivery-select-component
                                v-model="pay"
                                :delivery="product.delivery">
                            </delivery-select-component>
                        </option-select-component>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="xe-btn xe-btn-black xe-btn-block xe-btn-lg" @click="buyPage">구매하기</button>
                    </div>
                    <div class="col-3">
                        <button class="xe-btn xe-btn-block xe-btn-lg" @click="cartPage">장바구니</button>
                    </div>
                    <div class="col-3">
                        <button class="xe-btn xe-btn-block xe-btn-lg">찜하기</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div>
                    <b-tabs>
                        <b-tab title="상품정보" active>
                            <h4>상품 상세 정보</h4>
                            <table class="xe-table">
                                <tr v-for="each in detail()">
                                    <template v-for="(val,key) in each">
                                        <th>
                                            {{key}}
                                        </th>
                                        <td>
                                            {{val}}
                                        </td>
                                    </template>
                                </tr>
                            </table>
                            <div v-html="product.data.description">
                            </div>
                        </b-tab>
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
                        <b-tab title="반품정보">
                            <h4>반품정보</h4>
                            <div v-html="product.shop.as_info">

                            </div>
                        </b-tab>
                    </b-tabs>
                </div>
            </div>
        </div>
        <form ref="form">
        </form>
    </div>
</template>

<script>
    import OptionSelectComponent from '../../../../../../../../assets/src/components/OptionSelectComponent'
    import DeliverySelectComponent from '../../../../../../../../assets/src/components/DeliverySelectComponent'

    export default {
        name: "ProductDetailComponent",
        components: {
            OptionSelectComponent, DeliverySelectComponent
        },
        props: [
            'product', 'orderUrl', 'cartUrl', 'cartPageUrl'
        ],
        data() {
            return {
                mainImg: this.product.mainImage,
                choose: [],
                pay: '선불'
            }
        },
        methods: {
            changeMainImage(img) {
                this.mainImg = img
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
                console.log()
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
                return validate
            }
        },
        mounted() {
            console.log(this.product)
        }
    }
</script>

<style scoped>

</style>
