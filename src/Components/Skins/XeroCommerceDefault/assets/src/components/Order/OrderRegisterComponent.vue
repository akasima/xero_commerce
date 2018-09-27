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
                                <td>{{user.display_name}}</td>
                            </tr>
                            <tr>
                                <th>연락처</th>
                                <td>010-0000-0000</td>
                            </tr>
                            <tr>
                                <th>이메일</th>
                                <td>{{user.email}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <order-delivery-component :default="defaultDelivery" v-model="delivery"></order-delivery-component>
                <div class="card">
                    <div class="card-header">
                        <h4>결제 정보 입력</h4>
                    </div>
                    <div class="card-content">
                        <table class="table table-bordered">
                            <tr>
                                <th>간편 결제</th>
                                <td></td>
                                <th>일반 결제</th>
                                <td></td>
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
                <order-bill-component :summary="orderSummary" @pay="register"></order-bill-component>
                <order-agreement-component></order-agreement-component>
            </div>
        </div>
    </div>
</template>

<script>
  import OrderItemListComponent from './OrderItemListComponent'
  import OrderDeliveryComponent from './OrderDeliveryComponent'
  import OrderBillComponent from './OrderBillComponent'
  import OrderAgreementComponent from './OrderAgreementComponent'

  export default {
    name: "OrderRegisterComponent",
    components: {
      OrderDeliveryComponent, OrderBillComponent, OrderAgreementComponent, OrderItemListComponent
    },
    props: [
      'orderItemList', 'orderSummary', 'user', 'order_id'
    ],
    computed: {
      defaultDelivery() {
        return {
          name: (typeof this.user !== 'undefined') ? this.user.display_name : '',
          contact: ['010', '0000', '0000'],
          address: '서울 서초구 강남대로 527',
          address_detail: '14층 1404호',
          msg: '11층 우편함에 맡겨주세요'
        }
      }
    },
    data() {
      return {
        delivery: null
      }
    },
    methods: {
      register(pay) {
        if (pay.status) {
          $.ajax({
            url: '/plugin/xero_commerce/order/success/' + this.order_id,
            method: 'post',
            data: {
              delivery: this.delivery,
              _token: document.getElementById('csrf_token').value
            }
          }).done(this.complete).fail(this.fail())
        } else {
          document.location.href = '/plugin/xero_commerce/order/fail/' + this.order_id
        }
      },
      complete(res) {
        document.location.href = '/plugin/xero_commerce/order'
      },
      fail(error) {
        document.location.href = '/plugin/xero_commerce/order/fail/' + this.order_id
      }
    }
  }
</script>

<style scoped>
    .card-content table {
        margin: 0
    }
</style>