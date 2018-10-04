<template>
    <div class="card">
        <div class="card-header">
            <h4>배송지 정보</h4>
        </div>
        <div class="card-content">
            <table class="table table-bordered">
                <tr>
                    <th>배송지 선택</th>
                    <td>
                        <input type="radio" v-model="deliveryCheck" value="기본배송지" checked="checked"> 기본 배송지
                        <span v-for="del in userInfo.user_delivery" v-if="del.nickname!=='기본배송지'">
                        <input type="radio" v-model="deliveryCheck" :value="del.nickname">
                            {{del.nickname}}
                        </span>
                        <input type="radio" v-model="deliveryCheck" value="new"> 신규 배송지
                        <span v-show="this.deliveryCheck ==='new'"><input type="text" placeholder="신규배송지명" v-model="new_name"> <button @click="addDelivery">저장</button></span>
                    </td>
                </tr>
                <tr>
                    <th>이름</th>
                    <td><input type="text" v-model="delivery.name"></td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td>
                        <input type="text" v-model="delivery.phone">
                    </td>
                </tr>
                <tr>
                    <th>주소</th>
                    <td>
                        <input type="text">
                        <button @click="consoling">우편번호</button>
                        <input type="text" class="form-control" readonly="true" v-model="delivery.addr">
                        <input type="text" class="form-control" v-model="delivery.addr_detail">
                    </td>
                </tr>
                <tr>
                    <th>배송 메세지</th>
                    <td><input type="text" class="form-control" v-model="delivery.msg"></td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
  export default {
    name: "OrderDeliveryComponent",
    watch: {
      delivery (el) {
        this.$emit('input', el);
      },
      deliveryCheck (el) {
        var del = this.userInfo.user_delivery.find(v=>{return v.nickname === el})
        if (del)
        {
          this.delivery = {
            name: del.name,
            phone: del.phone,
            addr: del.addr,
            addr_detail: del.addr_detail,
            msg: del.msg,
            nickname: del.nickname
          }
        } else {
          this.delivery = Object.assign({}, this.newDelivery)
          if (el!=='new') this.delivery.nickname = el
        }
      }
    },
    props: [
        'input', 'userInfo'
    ],
    data () {
      return {
        deliveryCheck: '',
        newDelivery: {
          name: this.userInfo.name,
          phone: this.userInfo.phone,
          addr: '',
          addr_detail: '',
          msg: ''
        },
        new_name: '',
        delivery: {
          name: this.userInfo.name,
          phone: this.userInfo.phone,
          addr: '',
          addr_detail: '',
          msg: ''
        }
      }
    },
    methods: {
      clear (delivery) {
        delivery = {
          name: '',
          contact: [
            '', '', ''
          ],
          address: '',
          address_detail: '',
          msg: ''
        }
      },
      consoling () {
        console.log(this.delivery)
      },
      addDelivery () {
        this.delivery.nickname = this.new_name
        this.userInfo.user_delivery.push(this.delivery)
        this.deliveryCheck = this.new_name
        this.new_name = ''
      }
    },
    mounted () {
      this.deliveryCheck = '기본배송지'
      this.$emit('input', this.delivery)
    }
  }
</script>

<style scoped>

</style>