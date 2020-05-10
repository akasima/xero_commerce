<template>
    <div class="table-wrap">
        <h4 class="table-type-title">배송지 정보</h4>

        <div class="table-type" v-if="deliveryItems.length > 0">
            <div class="table-row">
                <div class="table-cell header">
                    배송지 선택
                </div>
                <div class="table-cell">
                    <label class="xe-label">
                        <input type="radio" checked="checked" name="addr" value="기본배송지" v-model="addressCheck">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">기본 배송지</span>
                    </label>
                    <label class="xe-label" v-for="del in userInfo.addresses" v-if="del.nickname!=='기본배송지'">
                        <input type="radio" name="addr" v-model="addressCheck" :value="del.nickname">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">{{del.nickname}}</span>
                    </label>
                    <label class="xe-label">
                        <input type="radio" name="addr" v-model="addressCheck" value="new">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">신규 배송지</span>
                    </label>
                    <span v-show="this.addressCheck ==='new'"><input type="text" placeholder="신규배송지명"
                                                                      v-model="new_name"> <button @click="addAddress">저장</button></span>
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    이름
                </div>
                <div class="table-cell">
                    <input type="text" class="xe-form-control input-195" v-model="address.name">
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    연락처
                </div>
                <div class="table-cell">
                    <div class="phone-number">
                        <div class="xe-select-box xe-btn table-select">
                            <label>{{phone1}}</label>
                            <select v-model="phone1" @change="address.phone=phone1+phone2+phone3">
                                <option>010</option>
                                <option>011</option>
                                <option>017</option>
                                <option>016</option>
                                <option>019</option>
                            </select>
                        </div>
                        <span class="margin-between">-</span>
                        <input type="text" class="xe-form-control" maxlength="4" v-model="phone2" @input="address.phone=phone1+phone2+phone3">
                        <span class="margin-between">-</span>
                        <input type="text" class="xe-form-control" maxlength="4" v-model="phone3" @input="address.phone=phone1+phone2+phone3">
                        <input type="hidden" v-model="address.phone">
                    </div> <!-- //table-cell-number -->
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    주소
                </div>
                <div class="table-cell">
                    <div class="table-cell-row">
                        <input type="text" class="xe-form-control input-only-72" v-model="address.addr_post" readonly @click="modal">
                        <button type="button" class="xe-btn xe-btn-secondary" @click="modal">우편번호</button>
                    </div>
                    <div class="table-cell-row">
                        <input type="text" class="xe-form-control input-195" readonly v-model="address.addr">
                        <input type="text" class="xe-form-control" v-model="address.addr_detail">
                    </div>
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    배송 메세지
                </div>
                <div class="table-cell">
                    <input type="text" class="xe-form-control table-input" v-model="address.msg">
                </div>
            </div>

        </div><!-- //table-type -->

        <div class="xe-modal" id="addressModal">
            <div class="xe-modal-dialog">
                <div class="xe-modal-content">
                    <div class="xe-modal-body">
                        <vue-daum-postcode @complete="addressRegister"
                                           style="height:300px;overflow-y: scroll"></vue-daum-postcode>
                    </div>
                </div>
            </div>
        </div>

      <div class="table-type" v-if="pickupItems.length > 0">
        <div class="table-row">
          <div class="table-cell header">
            픽업 장소
          </div>
          <div class="table-cell">
            <div v-for="item in pickupItems">
              {{ item.name }}<br>
              ㄴ [{{ item.shop_carrier.addr_post }}] {{ item.shop_carrier.addr }} {{ item.shop_carrier.addr_detail }}
            </div>
          </div>
        </div>

      </div><!-- //table-type -->

    </div><!-- //table-wrap -->
</template>

<script>
    export default {
        name: "OrderShipmentComponent",
        watch: {
            address(el) {
                this.$emit('input', el);
                this.phone1 = this.address.phone.slice(0,3)
                this.phone2 = this.address.phone.slice(3,7)
                this.phone3 = this.address.phone.slice(7,11)
            },
            addressCheck(el) {
                var del = this.userInfo.addresses.find(v => {
                    return v.nickname === el
                })
                if (del) {
                    this.address = {
                        name: del.name,
                        phone: del.phone,
                        addr: del.addr,
                        addr_detail: del.addr_detail,
                        addr_post:del.addr_post,
                        msg: del.msg,
                        nickname: del.nickname
                    }
                } else {
                    this.address = Object.assign({}, this.newAddress)
                    if (el !== 'new') this.address.nickname = el
                }
            }
        },
        props: [
            'input', 'userInfo', 'addressStoreUrl', 'orderItems'
        ],
        data() {
            return {
                addressCheck: '',
                newAddress: {
                    name: this.userInfo.name,
                    phone: this.userInfo.phone,
                    addr: '',
                    addr_detail: '',
                    addr_post:'',
                    msg: ''
                },
                new_name: '',
                address: {
                    name: this.userInfo.name,
                    phone: this.userInfo.phone,
                    addr: '',
                    addr_detail: '',
                    addr_post:'',
                    msg: ''
                },
                phone1: '010',
                phone2: this.userInfo.phone.slice(3,7),
                phone3: this.userInfo.phone.slice(7,11),
                deliveryItems: this.orderItems.filter(item => item.shop_carrier.carrier.type != 3),
                pickupItems: this.orderItems.filter(item => item.shop_carrier.carrier.type == 3)
            }
        },
        methods: {
            clear(address) {
                address = {
                    name: '',
                    contact: [
                        '', '', ''
                    ],
                    addr: '',
                    addr_detail: '',
                    addr_post:'',
                    msg: ''
                }
            },
            consoling() {
                console.log(this.address)
            },
            addAddress() {
                if(this.new_name===''){
                    alert('신규 배송지를 알아 볼 수 있는 닉네임을 설정해주세요')
                    return
                }
                if(this.address.addr===''){
                    alert('신규 배송지주소가 없습니다')
                    return
                }

                $.ajax({
                    url: this.addressStoreUrl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        _token: $('#csrf_token').val(),
                        nickname: this.new_name,
                        name: this.address.name,
                        phone: this.address.phone,
                        addr: this.address.addr,
                        addr_detail: this.address.addr_detail,
                        addr_post: this.address.addr_post,
                        msg: this.address.msg
                    }
                }).done((req)=>{
                    this.address.nickname = this.new_name
                    this.userInfo.addresses.push(this.address)
                    this.addressCheck = this.new_name
                    this.new_name = ''
                })
            },
            modal() {
                $('#addressModal').xeModal()
                setTimeout(() => {
                    console.log($(".post_search #region_name"))
                    $(".post_search #region_name").focus()
                }, 500)
            },
            addressRegister(res) {
                this.address.addr = res.address
                this.address.addr_post = res.zonecode
                $('#addressModal').xeModal('hide')
                $("#addr_detail").focus()
            }
        },
        mounted() {
            this.addressCheck = '기본배송지'
            this.$emit('input', this.address)
        }
    }
</script>

<style scoped>

</style>
