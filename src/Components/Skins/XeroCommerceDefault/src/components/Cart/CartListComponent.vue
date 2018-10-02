<template>
    <div>
        <table class="xe-table">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" v-model="allCheck">
                </th>
                <th></th>
                <th>상품정보</th>
                <th>상품금액</th>
                <th>할인금액</th>
                <th>수량</th>
                <th>배송비</th>
                <th>주문금액</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="cart in cartList">
                <td>
                    <input type="checkbox" v-model="checkedList" :value="cart.id" >
                </td>
                <td><img :src="cart.src" alt="" width="150px" height="150px"></td>
                <td>
                    <span v-for="(info,key) in cart.info" v-html="info"></span>
                </td>
                <td>
                    {{cart.original_price.toLocaleString()}}
                </td>
                <td>
                    {{cart.discount_price.toLocaleString()}}
                </td>
                <td>
                    {{cart.count}} 개 <br>
                    <button type="button" class="xe-btn xe-btn-default" @click="changeModal">변경</button>
                </td>
                <td>
                    선불
                </td>
                <td>
                    <b>{{cart.sell_price.toLocaleString()}} 원</b> <br>
                    <button class="btn xe-btn-black">주문하기</button>
                    <button class="btn btn-default">삭제하기</button>
                </td>
            </tr>
            </tbody>
        </table>
        <button class="xe-btn">선택상품 삭제</button>
        <button class="xe-btn">선택상품 찜</button>
        <div class="xe-modal" id="cartChangeModal" width="600px" height="800px">
            <div class="xe-modal-header">
                <h2>주문 추가/변경</h2>
            </div>
            <div class="xe-modal-content">
                <div class="xe-row">
                    <div class="xe-col-sm-3">

                    </div>
                    <div class="xe-col-sm-9">

                    </div>
                </div>
                <div class="xe-row">
                    <div class="xe-col-sm-12">
                        <table class="xe-table">
                            <tr>
                                <th>배송방법</th>
                                <td>택배</td>
                            </tr>
                            <tr>
                                <th>배송비</th>
                                <td>택배</td>
                            </tr>
                            <tr>
                                <th>배송비 결제</th>
                                <td>택배</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="xe-row">
                    <div class="xe-col-sm-12">
                        <table class="xe-table">
                            <tr>
                                <th>주문수량</th>
                                <td><input type="number"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: [
      'cartList'
    ],
    watch: {
      cartList () {
        this.checkedList = this.cartList.map((v)=>{return v.id});
      },
      checkedList (el) {
        this.$emit('checked', el);
      },
      allCheck (el) {
        if(el){
          this.checkedList = this.cartList.map((v)=>{return v.id});
        }else{
          this.checkedList = [];
        }
      }
    },
    name: "CartListComponent",
    data () {
      return {
        checkedList: [],
        allCheck: false
      }
    },
    methods: {
      changeModal () {
        $('#cartChangeModal').xeModal()
      }
    },
    mounted () {
      this.allCheck = true
    }
  }
</script>

<style scoped>

</style>