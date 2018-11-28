<template>
    <div class="xe-shop wishlist" id="component-container">
        <div class="xero-container">
            <div class="wishlist-wrap">
                <h1 class="page-title">찜한 상품</h1>
                <div class="table-wrap">
                    <h2 class="xe-sr-only">주문번호별 상품 내역</h2>
                    <div class="table-type3">
                        <div class="table-header xe-hidden-sm xe-hidden-xs">
                            <div class="table-row">
                                <div class="table-cell cell-input">
                                    <label class="xe-label">
                                        <input type="checkbox" v-model="allCheck">
                                        <span class="xe-input-helper"></span>
                                        <span class="xe-label-text"></span>
                                    </label>
                                </div>
                                <div class="table-cell cell-product">상품정보</div>
                                <div class="table-cell cell-number">상품금액</div>
                                <div class="table-cell cell-btn">선택</div>
                            </div>
                        </div> <!-- //table-header -->
                        <div class="table-body" v-for="item in list" style="width:100%">

                            <!-- [D] 복사 영역 주문별 영역 -->
                            <div class="table-row">
                                <div class="table-cell cell-input xe-hidden-sm xe-hidden-xs">
                                    <label class="xe-label">
                                        <input type="checkbox" v-model="checked" :value="item.id">
                                        <span class="xe-input-helper"></span>
                                        <span class="xe-label-text"></span>
                                    </label>
                                </div><!-- //table-cell -->
                                <div class="table-cell cell-product">
                                    <a href="#" @click="url(item.sellType.url)">
                                        <div class="cell-product-info">
                                            <div class="cell-product-img">
                                                <img :src="item.sellType.mainImage" alt="상품이미지">
                                            </div><!-- //cart-product-img -->
                                            <div class="cell-product-text">
                                                <div class="cell-product-name">
                                                    {{item.sellType.data.name}}
                                                </div><!-- //cart-product-name -->
                                            </div><!-- //cell-product-text -->
                                        </div><!-- //cell-product-info -->
                                    </a>

                                </div><!-- //table-cell -->
                                <div class="table-cell cell-number cell-first">
                                    <b class="xe-hidden-lg xe-hidden-md">상품금액</b>
                                    <span>{{item.sellType.data.sell_price.toLocaleString()}}</span>원

                                </div><!-- //table-cell -->
                                <div class="table-cell cell-btn cell-last">

                                    <span class="xe-sr-only"> 버튼 목록</span>
                                    <div class="cell-btn-wrap">
                                        <button type="button" class="btn-black" @click="option(item)">장바구니</button>
                                        <button type="button" class="btn-basics" @click="remove(item)">삭제하기</button>
                                    </div><!-- //cart-product-btn -->
                                    <div class="modal" :id="'wishOptionModal'+item.id">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="padding:20px">
                                                <div class="modal-header">
                                                    <h2>주문 추가/변경</h2>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <img :src="item.sellType.mainImage" alt="" width="100" height="100">
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <h3>{{item.sellType.data.name}}</h3>
                                                            <h3>{{item.sellType.data.sell_price.toLocaleString()}} 원</h3>
                                                        </div>
                                                    </div>
                                                    <option-select-component
                                                        :delivery="item.sellType.delivery"
                                                        v-model="item.choose"
                                                        :reset="toggle"
                                                        :already-choose="[]"
                                                        :options="item.sellType.options">
                                                        <delivery-select-component
                                                            v-model="pay"
                                                            :delivery="item.sellType.delivery">
                                                        </delivery-select-component></option-select-component>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="xe-btn xe-btn-black" @click="cart(item)">장바구니 담기</button>
                                                    <button class="xe-btn xe-btn-danger" data-dismiss="xe-modal" @click="closeModal(item)">닫기</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- //table-cell -->
                            </div> <!-- //table-row -->
                        </div><!-- //table-body -->
                    </div><!-- //table-type3 -->
                </div>

                <div class="bottom-btn-box">
                    <h2 class="xe-sr-only">관리 버튼</h2>
                    <button type="button" class="btn-default xe-hidden-sm xe-hidden-xs" @click="removeList">선택상품 삭제</button>
                    <button type="button" class="btn-default xe-hidden-lg xe-hidden-md">더 보기 <i class="xi-angle-down"></i></button>
                </div>

            </div><!-- //shipping-wrap -->
        </div><!-- //container  -->
    </div><!-- //shipping -->
</template>

<script>
    import OptionSelectComponent from '../OptionSelectComponent'
    import DeliverySelectComponent from '../DeliverySelectComponent'
    export default {
        name: "WishComponent",
        props: ['list', 'cartUrl', 'removeUrl', 'cartPageUrl'],
        components:{
            OptionSelectComponent, DeliverySelectComponent
        },
        watch: {
            allCheck (el) {
                if (el)
                {
                    this.checked = this.list.map(v=>{return v.id})
                }else {
                    this.checked = []
                }
            },
            choose (el) {
                console.log(el)
            }
        },
        data () {
            return {
                checked: [],
                allCheck: false,
                pay: '선불',
                toggle: true
            }
        },
        methods: {
            removeList () {
                var ids = this.checked
                $.ajax({
                    url:this.removeUrl,
                    method:'post',
                    data:{
                        ids: ids,
                        _token: document.getElementById('csrf_token').value
                    }
                }).done(()=>{
                        document.location.reload()
                    })
            },
            remove  (item) {
                this.checked = [item.id]
                this.removeList()
            },
            cart (item) {
                $.ajax({
                    url: this.cartUrl + '/' + item.sellType.id,
                    data: {
                        options: item.choose,
                        delivery: this.pay,
                        _token: document.getElementById('csrf_token').value
                    },
                    method: 'post'
                }).done(res => {
                    var conf = confirm('장바구니에 담았습니다. 장바구니로 갈까요?')
                    if (conf) {
                        document.location.href = this.cartPageUrl
                    }else{
                        $("#wishOptionModal"+item.id).modal('hide');
                    }
                })
            },
            option (item) {
                this.toggle=!this.toggle
                this.pay = '선불'
                $("#wishOptionModal"+item.id).modal('show');
            },
            closeModal(item) {
                $("#wishOptionModal"+item.id).modal('hide');
            },
            url(url) {
                document.location.href = url
            }
        },
        mounted () {
            console.log(this.list[0])
        }
    }
</script>

<style scoped>

</style>
