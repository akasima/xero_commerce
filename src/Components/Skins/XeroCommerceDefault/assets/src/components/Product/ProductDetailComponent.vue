<template>
    <div>
        <div class="row" style="margin-top: 20px">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <img :src="mainImg" style="width:100%">
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="overflow-x:scroll;height:60px; width:100%">
                        <img v-for="image in product.images" :src="image" style="width:50px" @click="changeMainImage(image)">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <h1>{{product.data.name}}</h1>
                        <p>{{product.data.sub_name}}</p>
                        <span style="text-decoration-line: line-through;color: #aaa">{{Number(product.data.original_price).toLocaleString()}} 원</span>
                        <h2>{{Number(product.data.sell_price).toLocaleString()}} 원</h2>
                        <option-select-component
                        :options="product.options"
                        :already-choose="[]"></option-select-component>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="xe-btn xe-btn-black xe-btn-block xe-btn-lg">구매하기</button>
                    </div>
                    <div class="col-3">
                        <button class="xe-btn xe-btn-block xe-btn-lg">장바구니</button>
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
                <h2>상품상세정보</h2>
                <div>
                    <b-tabs>
                        <b-tab title="상품정보" active>
                            <h4>상품 상세 정보</h4>
                            <table class="xe-table">
                                <tr>
                                    <th>
                                        상품상태
                                    </th>
                                    <td>
                                        상품싱태...
                                    </td>
                                    <th>
                                        상품번호
                                    </th>
                                    <td>
                                        상품번호...
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        제조사
                                    </th>
                                    <td>
                                        제조사...
                                    </td>
                                    <th>
                                        브랜드
                                    </th>
                                    <td>
                                        브랜드...
                                    </td>
                                </tr>
                            </table>
                            <div v-html="product.data.description">
                            </div>
                        </b-tab>
                        <b-tab title="구매평">
                            구매평
                        </b-tab>
                        <b-tab title="Q&A">
                            Q&A
                        </b-tab>
                        <b-tab title="반품정보">
                            반품정
                        </b-tab>
                    </b-tabs>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import OptionSelectComponent from '../OptionSelectComponent'
  export default {
    name: "ProductDetailComponent",
    components: {
      OptionSelectComponent
    },
    props: [
        'product'
    ],
    data () {
      return {
        mainImg: this.product.mainImage
      }
    },
    methods: {
      changeMainImage (img) {
        this.mainImg = img
      }
    },
    mounted () {
      console.log(this.product)
    }
  }
</script>

<style scoped>

</style>