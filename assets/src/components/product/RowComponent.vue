<template>
    <tr>
        <template v-if="!loading">
            <td v-if="isShowState">{{ optionList.option_type_name }}</td>
            <td v-if="isShowState" v-model="optionList.name">{{ optionList.name }}</td>
            <td v-if="isShowState">{{ optionList.addition_price.toLocaleString() }}</td>
            <td v-if="isShowState">{{ optionList.sell_price.toLocaleString() }}</td>
            <td v-if="isShowState">{{ optionList.stock.toLocaleString() }}</td>
            <td v-if="isShowState">{{ optionList.alert_stock.toLocaleString() }}</td>
            <td v-if="isShowState">{{ (Number(optionList.state_display)==1)?'출력':'미출력' }}</td>
            <td v-if="isShowState">{{ (Number(optionList.state_deal)==1)?'판매':((Number(optionList.state_deal)==2)?'일시중단':'중단' )}}</td>
            <td v-if="isShowState">
                <button type="button" v-on:click="toggleShowState" class="xe-btn">수정</button>
            </td>

            <td v-if="!isShowState">
                <div v-if="optionList.data.option_type===1">
                    기본
                </div>
                <div v-if="optionList.data.option_type!==1">
                    <input type="radio" class="form-control" v-model="optionList.data.option_type" name="type" value="2"> 옵션
                    <input type="radio" class="form-control" v-model="optionList.data.option_type" name="type" value="3"> 추가
                </div>
            </td>
            <td v-if="!isShowState"><input type="text" class="form-control" v-model="optionList.data.name"></td>
            <td v-if="!isShowState"><input type="text" class="form-control" v-model="optionList.data.addition_price"></td>
            <td v-if="!isShowState">{{(Number(optionList.data.addition_price) + Number(optionList.data.product.sell_price)).toLocaleString()}}</td>
            <td v-if="!isShowState"><input type="text" class="form-control" v-model="optionList.data.stock"></td>
            <td v-if="!isShowState"><input type="text" class="form-control" v-model="optionList.data.alert_stock"></td>
            <td v-if="!isShowState"><input type="radio" class="form-control" v-model="optionList.data.state_display" value="1" name="display">출력  <input type="radio" class="form-control" v-model="optionList.data.state_display" name="display" value="2">미출력</td>
            <td v-if="!isShowState"><input type="radio" class="form-control" v-model="optionList.data.state_deal" value="1" name="deal">판매<input type="radio" class="form-control" v-model="optionList.data.state_deal" value="2" name="deal">일시중단<input type="radio" class="form-control" v-model="optionList.state_deal" value="3" name="deal">중단</td>
            <td v-if="!isShowState">
                <button type="button" v-on:click="toggleShowState();save(optionList)" class="xe-btn">수정</button>
                <button type="button" v-on:click="toggleShowState();remove(optionList)" class="xe-btn xe-btn-danger">삭제</button>
            </td>
        </template>
        <td colspan="9" style="text-align: center" v-if="loading">
            로딩중
        </td>
    </tr>
</template>

<script>
    export default {
        watch:{
            optionData (el) {
                this.optionList = el
                this.loading=false
            }
        },
        name: "RowComponent",
        props: [
            'optionData'
        ],
        compouted: {
            readyAndShwState () {
                return !this.loading && isShowState;
            }
        },
        data() {
            return {
                optionList: this.optionData,
                isShowState : true,
                loading: false
            }
        },
        methods: {
            toggleShowState : function () {
                this.isShowState = !this.isShowState;
            },
            save (item) {
                this.loading = true
                this.$emit('save',item.data)
            },
            remove (item) {
                this.loading = true
                this.$emit('remove',item.data)
            }
        },
        mounted() {
            console.log(this.optionList)
        }
    }
</script>

<style scoped>

</style>
