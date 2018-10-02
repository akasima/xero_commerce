<template>
    <div class="card">
        <div class="card-header">
            <h4>배송지 정보</h4>
        </div>
        <div class="card-content">
            <table class="table table-bordered">
                <tr>
                    <th>배송지 선택</th>
                    <td><input type="radio" v-model="deliveryCheck" value="default" checked="checked"> 기본 배송지 <input type="radio" v-model="deliveryCheck" value="new"> 신규 배송지</td>
                </tr>
                <tr>
                    <th>이름</th>
                    <td><input type="text" :readonly="deliveryCheck === 'default'" v-model="delivery.name"></td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td>010-<input type="text" v-model="delivery.contact[1]"  :readonly="deliveryCheck === 'default'">-<input type="text" v-model="delivery.contact[2]"  :readonly="deliveryCheck === 'default'"></td>
                </tr>
                <tr>
                    <th>주소</th>
                    <td>
                        <input type="text"  :readonly="deliveryCheck === 'default'">
                        <button>우편번호</button>
                        <input type="text" class="form-control" readonly="true" v-model="delivery.address">
                        <input type="text" class="form-control" :readonly="deliveryCheck === 'default'" v-model="delivery.address_detail">
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
      }
    },
    props: [
        'default', 'input'
    ],
    computed: {
      delivery () {
        return (this.deliveryCheck === 'default') ? this.default : this.newDelivery
      }
    },
    data () {
      return {
        deliveryCheck: 'default',
        newDelivery: {
          name: '',
          contact: [
              '', '', ''
          ],
          address: '',
          address_detail: '',
          msg: ''
        }
      }
    },
    mounted () {
      this.$emit('input', this.delivery)
    }
  }
</script>

<style scoped>

</style>