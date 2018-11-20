<template>
    <div class="table-wrap">
        <h4 class="table-type-title">배송지 정보</h4>
        <button type="button" class="btn-cart-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i>
        </button>
        <div class="table-type">
            <div class="table-row">
                <div class="table-cell header">
                    배송지 선택
                </div>
                <div class="table-cell">
                    <label class="xe-label">
                        <input type="radio" checked="checked" name="addr" value="기본배송지" v-model="deliveryCheck">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">기본 배송지</span>
                    </label>
                    <label v-for="del in userInfo.user_delivery" v-if="del.nickname!=='기본배송지'">
                        <input type="radio" name="addr" v-model="deliveryCheck" :value="del.nickname">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">{{del.nickname}}</span>
                    </label>
                    <label class="xe-label">
                        <input type="radio" name="addr" v-model="deliveryCheck" value="new">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">신규 배송지</span>
                    </label>
                    <span v-show="this.deliveryCheck ==='new'"><input type="text" placeholder="신규배송지명"
                                                                      v-model="new_name"> <button @click="addDelivery">저장</button></span>
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    이름
                </div>
                <div class="table-cell">
                    <input type="text" class="xe-form-control input-195" v-model="delivery.name">
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
                            <select v-model="phone1" @change="delivery.phone=phone1+phone2+phone3">
                                <option>010</option>
                                <option>011</option>
                                <option>017</option>
                                <option>016</option>
                                <option>019</option>
                            </select>
                        </div>
                        <span class="margin-between">-</span>
                        <input type="text" class="xe-form-control" maxlength="4" v-model="phone2" @input="delivery.phone=phone1+phone2+phone3">
                        <span class="margin-between">-</span>
                        <input type="text" class="xe-form-control" maxlength="4" v-model="phone3" @input="delivery.phone=phone1+phone2+phone3">
                        <input type="hidden" v-model="delivery.phone">
                    </div> <!-- //table-cell-number -->
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    주소
                </div>
                <div class="table-cell">
                    <div class="table-cell-row">
                        <input type="text" class="xe-form-control input-only-72" v-model="delivery.addr_post" readonly @click="modal">
                        <button type="button" class="xe-btn xe-btn-secondary" @click="modal">우편번호</button>
                    </div>
                    <div class="table-cell-row">
                        <input type="text" class="xe-form-control input-195" readonly v-model="delivery.addr">
                        <input type="text" class="xe-form-control" v-model="delivery.addr_detail">
                    </div>
                </div>
            </div>

            <div class="table-row">
                <div class="table-cell header">
                    배송 메세지
                </div>
                <div class="table-cell">
                    <input type="text" class="xe-form-control table-input" v-model="delivery.msg">
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
    </div><!-- //table-wrap -->
</template>

<script>
    export default {
        name: "OrderDeliveryComponent",
        watch: {
            delivery(el) {
                this.$emit('input', el);
                this.phone1 = this.delivery.phone.slice(0,3)
                this.phone2 = this.delivery.phone.slice(3,7)
                this.phone3 = this.delivery.phone.slice(7,11)
            },
            deliveryCheck(el) {
                var del = this.userInfo.user_delivery.find(v => {
                    return v.nickname === el
                })
                if (del) {
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
                    if (el !== 'new') this.delivery.nickname = el
                }
            }
        },
        props: [
            'input', 'userInfo'
        ],
        data() {
            return {
                deliveryCheck: '',
                newDelivery: {
                    name: this.userInfo.name,
                    phone: this.userInfo.phone,
                    addr: '',
                    addr_detail: '',
                    addr_post:'',
                    msg: ''
                },
                new_name: '',
                delivery: {
                    name: this.userInfo.name,
                    phone: this.userInfo.phone,
                    addr: '',
                    addr_detail: '',
                    addr_post:'',
                    msg: ''
                },
                phone1: '010',
                phone2: this.userInfo.phone.slice(3,7),
                phone3: this.userInfo.phone.slice(7,11)
            }
        },
        methods: {
            clear(delivery) {
                delivery = {
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
                console.log(this.delivery)
            },
            addDelivery() {
                this.delivery.nickname = this.new_name
                this.userInfo.user_delivery.push(this.delivery)
                this.deliveryCheck = this.new_name
                this.new_name = ''
            },
            modal() {
                $('#addressModal').xeModal()
                setTimeout(() => {
                    console.log($(".post_search #region_name"))
                    $(".post_search #region_name").focus()
                }, 500)
            },
            addressRegister(res) {
                this.delivery.addr = res.address
                this.delivery.addr_post = res.zonecode
                $('#addressModal').xeModal('hide')
                $("#addr_detail").focus()
            }
        },
        mounted() {
            this.deliveryCheck = '기본배송지'
            this.$emit('input', this.delivery)
        }
    }
</script>

<style scoped>

</style>
