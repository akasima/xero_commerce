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
                <td style="cursor: pointer;" @click="url('/shopping/'+cart.name)">
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
                    <button type="button" class="xe-btn xe-btn-default" @click="changeModal(cart)">변경</button>
                    <div class="modal" :id="'cartChangeModal'+cart.id">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>주문 추가/변경</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <img :src="cart.src" alt="" width="100" height="100">
                                        </div>
                                        <div class="col-sm-9">
                                            <h3>{{cart.name}}</h3>
                                            <h3>{{cart.sell_price.toLocaleString()}} 원</h3>
                                        </div>
                                    </div>
                                    <option-select-component
                                            v-model="cart.choose"
                                            :already-choose="cart.choose"
                                            :options="cart.option_list"></option-select-component>
                                </div>
                                <div class="modal-footer">
                                    <button class="xe-btn xe-btn-black" @click="edit(cart)">저장하고 계속</button>
                                    <button class="xe-btn xe-btn-danger" data-dismiss="xe-modal">저장하지않고 닫기</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    선불
                </td>
                <td>
                    <b>{{cart.sell_price.toLocaleString()}} 원</b> <br>
                    <button class="btn xe-btn-black" @click="onlyThisCart(cart.id)">주문하기</button>
                    <button class="btn btn-default" @click="draw(cart.id)">삭제하기</button>
                </td>
            </tr>
            </tbody>
        </table>
        <button class="xe-btn">선택상품 삭제</button>
        <button class="xe-btn">선택상품 찜</button>
    </div>
</template>

<script>
    import OptionSelectComponent from '../OptionSelectComponent'
  export default {
    components: {
      OptionSelectComponent
    },
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
      },
      selectOption (el) {
        var exist = this.selectedCart.choose.find(v=>{return v.unit.id === el.id})
        if (exist) {
          exist.count ++
        }else {
          this.selectedCart.choose.push({
            id: null,
            unit: el,
            count: 1
          })
        }
      }
    },
    name: "CartListComponent",
    computed: {
      totalChoosePrice () {
        return this.sum(this.selectedCart.choose)
      }
    },
    data () {
      return {
        checkedList: [],
        allCheck: false,
        selectedCart: {
          src: '',
          sell_price: 0,
          info: '',
          choose: []
        },
        selectOption: null
      }
    },
    methods: {
      changeModal (cart) {
        $('#cartChangeModal'+cart.id).xeModal()
      },
      sum(array){
        return array.map((v) => {
          return v.unit.sell_price * v.count
        }).reduce((a, b) => a + b, 0);
      },
      edit(cart){
        $.ajax({
          url: '/shopping/cart/change/' + cart.id,
          data: cart
        }).done(()=>{
          this.$emit('change')
        }).fail(()=>{
          console.log('fail')
        })
      },
      draw(cart_id){
        $.ajax({
          url: '/shopping/cart/draw/' + cart_id
        }).done(()=>{
          this.$emit('change')
        }).fail(()=>{
          console.log('fail')
        })
      },
      dropOption(key){
        this.selectedCart.choose.splice(key, 1)
      },
      onlyThisCart(cart_id){
        this.$emit('only', cart_id)
      },
      url (url) {
        window.open(url, 'target=_blank')
      }
    },
    mounted () {
      this.allCheck = true
    }
  }
</script>

<style scoped>

</style>