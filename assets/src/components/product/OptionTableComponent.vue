<template>
    <table class="xe-table">
        <thead>
        <tr>
            <td colspan="9">
                <a href="#" @click.prevent="add"><i class="xi-plus"></i></a>
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
        <tr v-for="(option, key) in addList">
            <td>
                <input type="radio" v-model="option.option_type" value="2" class="form-control"/> 옵션상품
                <input type="radio" v-model="option.option_type" value="3" class="form-control"/> 추가상품
            </td>
            <td><input type="text" v-model="option.name" class="form-control"></td>
            <td><input type="number" v-model="option.addition_price" class="form-control"></td>
            <td>{{(Number(option.addition_price)+Number(productPrice)).toLocaleString()}}</td>
            <td><input type="number" v-model="option.stock" class="form-control"></td>
            <td><input type="number" v-model="option.alert_stock" class="form-control"></td>
            <td>
                <input type="radio" v-model="option.state_display" value="1" class="form-control"/>출력
                <input type="radio" v-model="option.state_display" value="2" class="form-control"/>미출력
            </td>
            <td>
                <input type="radio" v-model="option.state_deal" value="1" class="form-control"/>판매중
                <input type="radio" v-model="option.state_deal" value="2" class="form-control"/>일시 중단
                <input type="radio" v-model="option.state_deal" value="3" class="form-control"/>중단
            </td>
            <td>
                <button class="btn btn-default" @click="save(option); pop(key)">저장</button>
                <button class="btn btn-danger" @click="pop(key)">삭제</button>
            </td>
        </tr>
        </tbody>
    </table>
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
                    option_type: '',
                    name: '',
                    addition_price: '',
                    stock: '',
                    alert_stock: '',
                    state_display: '',
                    state_deal: '',
                })
            },
            pop(key) {
                this.addList.splice(key,1);
            }
        },
        mounted() {
            console.log(this.optionList[0])
        }
    }
</script>

<style scoped>

</style>
