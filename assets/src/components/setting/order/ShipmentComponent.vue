<template>
    <div class="panel">
        <div class="panel-body">
            <div class="table-scrollable">
                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" v-model="allCheck"></th>
                        <th>주문번호</th>
                        <th>상세정보</th>
                        <th>주소</th>
                        <th>배송사</th>
                        <th>송장번호</th>
                        <th>입력완료</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order_item in orderItems">
                        <td>
                            <input v-model="checked" type="checkbox" :value="order_item.id">
                        </td>
                        <td>
                            {{order_item.order_no}}
                            <p style="text-align: center; font-weight: bold;">[{{order_item.status}}]</p>
                        </td>
                        <td>
                            <span v-for="html in order_item.info" v-html="html"></span>
                        </td>
                        <td>
                            {{order_item.shipment.recv_addr + ' ' + order_item.shipment.recv_addr_detail}} <br>
                            수령인 : {{order_item.shipment.recv_name}}
                        </td>
                        <td>
                            {{order_item.shipment.carrier.name}}
                        </td>
                        <td>
                            <span v-if="order_item.shipment.ship_no !==''">{{order_item.shipment.ship_no}}</span>
                            <input v-if="order_item.shipment.ship_no ===''" type="text" v-model="texted[order_item.id]">
                        </td>
                        <td>
                            <button v-if="order_item.shipment.ship_no ===''" @click="select(order_item.id)">배송중</button>
                            <button v-if="order_item.status !=='완료'" @click="selectComplete(order_item.id)">배송완료</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-offset-7 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" v-model="allNo">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" @click="submit">일괄 배송중</button>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" type="button" @click="complete">일괄 배송완료</button>
                </div>
            </div>
        </div>
        <div class="xero-settings-control-float">
            <button class="btn btn-link" type="button" @click="downloadTemplate">엑셀양식다운로드</button>
            <button class="btn btn-default" type="button" @click="showExcelUpload">엑셀업로드</button>
        </div>
        <div class="modal" id="excelUpload">
            <form action="/settings/xero_commerce/orders/shipment/excel" method="post" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content" style="padding:20px">
                    <h3>엑셀을 이용한 송장번호 입력</h3>
                        <input type="hidden" name="_token" :value="token">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>파일첨부</label>
                                <input type="file" name="shipment" class="form-control">
                                <small>반드시 <button class="btn btn-link" type="button" @click="downloadTemplate">엑셀양식다운로드</button>를 이용해 받은 엑셀양식으로만 작성해주세요.</small>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" class="xe-btn xe-btn-black">업로드</button>
                        <button type="button" class="xe-btn xe-btn-danger" @click="hideExcelUpload">취소</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShipmentComponent",
        props: [
            'orderItems', 'token'
        ],
        watch: {
            allNo(el) {
                console.log(el)
                this.checked.forEach(v => {
                    this.texted[v] = el
                })
            },
            allCheck(el) {
                this.checked = (el) ? this.orderItems.map(v => {
                    return v.id
                }) : [];
            }
        },
        computed: {
            shipment() {
                return this.checked.map(v => {
                    return {
                        id: v,
                        no: this.texted[v]
                    }
                })
            }
        },
        data() {
            return {
                checked: [],
                texted: [],
                allNo: '370269846894',
                allCheck: false
            }
        },
        mounted() {
            console.log(this.orderItems)
        },
        methods: {
            select(id) {
                this.checked = [id]
                this.submit()
            },
            selectComplete(id) {
                this.checked = [id]
                this.complete()
            },
            submit() {
                this.texted = []
                $.each(this.checked, (k, v) => {
                    console.log(this.checked)
                    console.log(v)
                    this.texted[v] = this.allNo
                })
                if (this.validate()) {
                    $.ajax({
                        url: '/settings/xero_commerce/orders/shipment',
                        method: 'post',
                        data: {
                            shipment: this.shipment,
                            _token: this.token
                        }
                    }).done(() => {
                        document.location.reload()
                    }).fail((err) => {
                        console.log(err)
                    })
                }
            },
            validate() {
                var err = [];
                this.shipment.forEach(v => {
                    if (typeof v.no === 'undefined' || String(v.no) === '') {
                        err.push(v)
                    }
                })
                if (err.length > 0) {
                    alert('송장번호 입력이 안된 주문 또는 이미 배송중인 주문이 있습니다.')
                    return false;
                } else {
                    return true;
                }
            },
            complete() {
                $.ajax({
                    url: '/settings/xero_commerce/orders/shipment/complete',
                    method: 'post',
                    data: {
                        shipment: this.checked,
                        _token: this.token
                    }
                }).done(() => {
                    document.location.reload()
                }).fail((err) => {
                    console.log(err)
                })
            },
            showExcelUpload() {
                $('#excelUpload').modal('show');
            },
            hideExcelUpload() {
                $('#excelUpload').modal('hide');
            },
            downloadTemplate() {
                location.href='/settings/xero_commerce/orders/shipment/excel';
            }
        }
    }
</script>

<style scoped>

</style>
