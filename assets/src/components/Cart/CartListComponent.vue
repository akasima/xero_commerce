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
                    <button type="button" class="xe-btn xe-btn-default"">변경</button>
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
        allCheck: true
      }
    }
  }
</script>

<style scoped>

</style>