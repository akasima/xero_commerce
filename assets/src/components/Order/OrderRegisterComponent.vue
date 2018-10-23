<template>
    <div>
        <order-item-list-component :order-item-list="orderItemList"></order-item-list-component>
        <div class="xe-row">
            <div class="xe-col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>주문고객 정보</h4>
                    </div>
                    <div class="card-content">
                        <table class="table table-bordered">
                            <tr>
                                <th>이름</th>
                                <td>{{userInfo.name}}</td>
                            </tr>
                            <tr>
                                <th>연락처</th>
                                <td>{{userInfo.phone}}</td>
                            </tr>
                            <tr>
                                <th>이메일</th>
                                <td>{{user.email}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <order-delivery-component :user-info="userInfo" v-model="delivery"></order-delivery-component>
                <div class="card">
                    <div class="card-header">
                        <h4>결제 정보 입력</h4>
                    </div>
                    <div class="card-content">
                        <table class="table table-bordered">
                            <tr>
                                <th>일반 결제</th>
                                <td>
                                    <pay-component :pay-methods="payMethods"></pay-component>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>할인 정보</h4>
                    </div>
                    <div class="card-content">
                        <table class="table table-bordered">
                            <tr>
                                <th>할인 쿠폰</th>
                                <td><input type="text" value="0">원</td>
                            </tr>
                            <tr>
                                <th>적립금 사용</th>
                                <td><input type="text" value="0">원</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="xe-col-lg-4">
                <order-bill-component :summary="orderSummary"
                                      :validate="validate"
                                      :method="payMethod"
                                      @pay="register"
                                      ></order-bill-component>
                <order-agreement-component :agreements="agreements" v-model="agreed" :agree-url="agreeUrl" :denied-url="deniedUrl"></order-agreement-component>
            </div>
        </div>
    </div>
</template>

<script>
  import OrderItemListComponent from './OrderItemListComponent'
  import OrderDeliveryComponent from './OrderDeliveryComponent'
  import OrderBillComponent from './OrderBillComponent'
  import OrderAgreementComponent from './OrderAgreementComponent'
  import PayComponent from './PayComponent'

  export default {
    name: "OrderRegisterComponent",
    components: {
      OrderDeliveryComponent, OrderBillComponent, OrderAgreementComponent, OrderItemListComponent, PayComponent
    },
    props: [
      'orderItemList', 'orderSummary', 'user', 'userInfo', 'order_id', 'dashUrl', 'successUrl', 'failUrl', 'agreements', 'payMethods', 'agreeUrl', 'deniedUrl'
    ],
    computed: {
      validate() {
        var res = {
          status: true,
          msg: ''
        }
        if (this.agreed.length < 3) {
          res.msg += '3가지 약관에 모두 동의후에 결제가 진행됩니다.\n\r'
          res.status = false
        }
        if (this.delivery === null) {
          res.msg += '주소가 불분명합니다.\n\r'
          res.status = false
        } else {
          if (this.delivery.addr === '') {
            res.msg += '주소가 불분명합니다.\n\r'
            res.status = false
          }
          if (this.delivery.addr_detail === '') {
            res.msg += '상세주소를 적어주세요.\n\r'
            res.status = false
          }
        }
        return res
      }
    },
    data() {
      return {
        delivery: null,
        payMethod: null,
        easyMethodList: [],
        agreed: []
      }
    },
    methods: {
      register(pay) {
        if (pay.status) {
          $.ajax({
            url: this.successUrl,
            method: 'post',
            data: {
              delivery: this.delivery,
              _token: document.getElementById('csrf_token').value
            }
          }).done(this.complete).fail(this.fail())
        } else {
          document.location.href = this.failUrl
        }
      },
      complete(res) {
        document.location.href = this.dashUrl
      },
      fail(error) {
        console.log(error)
        //document.location.href = this.failUrl
      }
    },
    mounted () {
      console.log(this.payMethods)
    }
  }
</script>

<style scoped>
    .card-content table {
        margin: 0
    }
</style>
