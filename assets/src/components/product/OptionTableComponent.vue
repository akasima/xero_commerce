<template>
    <div class="table-responsive">
        <div class="form-group">
            <a href="#add" class="btn btn-sm btn-primary" @click.prevent="add"><i class="xi-plus"></i> 옵션 추가</a>
        </div>
        <table class="table">
            <thead>
            <tr v-for="(option, key) in addList">
                <td colspan="9">
                    <div class="xe-row">
                        <div class="xe-col-sm-6">
                            <div class="form-group">
                                <label class ="control-label col-sm-3">타입</label>
                                <div class="col-sm-8">
                                    <input type="radio" v-model="option.option_type" value="2" /> 옵션상품
                                    <input type="radio" v-model="option.option_type" value="3" /> 추가상품
                                </div>
                            </div>
                            <div class="form-group">
                                <label class ="control-label col-sm-3">옵션명</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="option.name">
                                </div>
                            </div>
                        </div>
                        <div class="xe-col-sm-6">
                            <div class="form-group">
                                <label class ="control-label col-sm-3">추가금액</label>
                                <div class="col-sm-8">
                                    <input type="number" v-model="option.addition_price" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class ="control-label col-sm-3">현재재고</label>
                                <div class="col-sm-8">
                                    <input type="number" v-model="option.stock" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class ="control-label col-sm-3">품절 알림 재고</label>
                                <div class="col-sm-8">
                                    <input type="number" v-model="option.alert_stock" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xe-row">
                        <div class="xe-col-sm-6">
                            <div class="form-group">
                                <label class ="control-label col-sm-3">출력상태</label>
                                <div class="col-sm-8">
                                    <input type="radio" v-model="option.state_display" value="1" />출력
                                    <input type="radio" v-model="option.state_display" value="2" />미출력
                                </div>
                            </div>
                        </div>
                        <div class="xe-col-sm-6">
                            <div class="form-group">
                                <label class ="control-label col-sm-3">판매상태</label>
                                <div class="col-sm-8">
                                    <input type="radio" v-model="option.state_deal" value="1" />판매중
                                    <input type="radio" v-model="option.state_deal" value="2" />일시 중단
                                    <input type="radio" v-model="option.state_deal" value="3" />중단
                                </div>
                            </div>
                            <div class="form-group" style="text-align: right;">
                                <button class="btn btn-default" @click="save(option); pop(key)">저장</button>
                                <button class="btn btn-danger" @click="pop(key)">삭제</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>옵션 타입</th>
                <th>옵션명</th>
                <th>추가 금액</th>
                <th>실 구매 금액</th>
                <th>현재 재고</th>
                <th>품절 알림 재고</th>
                <th>출력 상태</th>
                <th>판매 상태</th>
                <th>관리</th>
            </tr>
            </thead>

            <tbody>
            <row-component v-for="(option,key) in optionList" v-bind:optionData="option" :key="key" @save="save"
                        @remove="remove"></row-component>
            </tbody>
        </table>
    </div>
</template>

<script>
    import RowComponent from './RowComponent'

    export default {
        name: "OptionTableComponent",
        components: {
            RowComponent
        },
        props: [
            'options', 'saveUrl', 'removeUrl', 'loadUrl', 'productId', 'productPrice'
        ],
        data() {
            return {
                optionList: this.options,
                addList: []
            }
        },
        methods: {
            save(item) {
                item._token = $('input[name=_token]').val()
                $.ajax({
                    url: this.saveUrl,
                    method: 'post',
                    data: item
                }).done(() => {
                    this.load()
                })
            },
            remove(item) {
                if (item.id) {
                    item._token = $('input[name=_token]').val()
                    $.ajax({
                        url: this.removeUrl,
                        method: 'post',
                        data: item
                    }).done(() => {
                        this.load()
                    })
                }
            },
            load() {
                $.ajax({
                    url: this.loadUrl,
                    method: 'get'
                }).done(res => {
                    this.optionList = res
                })
            },
            add() {
                this.addList.push({
                    product_id: this.productId,
                    option_type: 2,
                    name: '',
                    addition_price: '',
                    stock: '',
                    alert_stock: '',
                    state_display: 1,
                    state_deal: 1,
                })
            },
            pop(key) {
                this.addList.splice(key,1);
            }
        }
    }
</script>

<style scoped>

</style>
