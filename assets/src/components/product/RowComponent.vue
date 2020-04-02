<template>
    <tr>
        <td v-if="isShowState">{{ optionItem.name }}</td>
        <td v-if="isShowState">{{ Number(optionItem.addition_price).toLocaleString() }}</td>
        <td v-if="isShowState">{{ Number(optionItem.stock).toLocaleString() }}</td>
        <td v-if="isShowState">{{ Number(optionItem.alert_stock).toLocaleString() }}</td>
        <td v-if="isShowState">{{ (Number(optionItem.state_display)==1)?'출력':'미출력' }}</td>
        <td v-if="isShowState">{{ (Number(optionItem.state_deal)==1)?'판매':((Number(optionItem.state_deal)==2)?'일시중단':'중단' )}}</td>
        <td v-if="isShowState">
            <button type="button" v-on:click="toggleShowState" class="btn btn-sm btn-default">수정</button>
        </td>


        <input type="hidden" :name="`option_items[${index}][value_combination]`" :value="JSON.stringify(optionItem.value_combination)" />
        <td v-show="!isShowState"><input type="text" :name="`option_items[${index}][name]`" class="form-control" v-model="optionItem.name"></td>
        <td v-show="!isShowState"><input type="text" :name="`option_items[${index}][addition_price]`" class="form-control" v-model="optionItem.addition_price"></td>
        <td v-show="!isShowState"><input type="text" :name="`option_items[${index}][stock]`" class="form-control" v-model="optionItem.stock"></td>
        <td v-show="!isShowState"><input type="text" :name="`option_items[${index}][alert_stock]`" class="form-control" v-model="optionItem.alert_stock"></td>
        <td v-show="!isShowState">
            <div class="radio"><label><input type="radio" :name="`option_items[${index}][state_display]`" v-model="optionItem.state_display" value="1" />출력</label></div>
            <div class="radio"><label><input type="radio" :name="`option_items[${index}][state_display]`" v-model="optionItem.state_display" value="2" />미출력</label></div>
        </td>
        <td class="nowrap" v-show="!isShowState">
            <div class="radio"><label><input type="radio" :name="`option_items[${index}][state_deal]`" v-model="optionItem.state_deal" value="1" />판매</label></div>
            <div class="radio"><label><input type="radio" :name="`option_items[${index}][state_deal]`" v-model="optionItem.state_deal" value="2" />일시중단</label></div>
            <div class="radio"><label><input type="radio" :name="`option_items[${index}][state_deal]`" v-model="optionItem.state_deal" value="3" />중단</label></div>
        </td>
        <td class="nowrap" v-if="!isShowState">
            <button type="button" v-on:click="toggleShowState();" class="btn btn-sm btn-default">저장</button>
            <button type="button" v-on:click="toggleShowState(); remove()" class="btn btn-sm btn-danger">삭제</button>
        </td>
    </tr>
</template>

<script>
    export default {
        watch:{
            // 외부에서 optionItemData가 변경되면 데이터 동기화
            optionItemData (val) {
                this.optionItem = val
            }
        },
        name: "RowComponent",
        props: [
            'optionItemData'
        ],
        data() {
            return {
                optionItem: this.optionItemData,
                isShowState : true,
                index: this.$vnode.key
            }
        },
        methods: {
            toggleShowState : function () {
                this.isShowState = !this.isShowState;
            },
            remove () {
                this.$emit('remove', this.index)
            }
        }
    }
</script>

<style scoped>

</style>
