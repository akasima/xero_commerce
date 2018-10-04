<template>
    <tr v-if="true">
        <td v-if="isShowState">{{ optionData.option_type_name }}</td>
        <td v-if="isShowState" v-model="optionData.name">{{ optionData.name }}</td>
        <td v-if="isShowState">{{ optionData.addition_price }}</td>
        <td v-if="isShowState">{{ optionData.sell_price }}</td>
        <td v-if="isShowState">{{ optionData.stock }}</td>
        <td v-if="isShowState">{{ optionData.alert_stock }}</td>
        <td v-if="isShowState">{{ optionData.state_display }}</td>
        <td v-if="isShowState">{{ optionData.state_deal }}</td>
        <td v-if="isShowState">
            <button type="button" v-on:click="toggleShowState" class="xe-btn">수정</button>
        </td>

        <td v-if="!isShowState"><input type="text" v-model="optionData.option_type_name"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.name"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.addition_price"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.sell_price"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.stock"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.alert_stock"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.state_display"></td>
        <td v-if="!isShowState"><input type="text" v-model="optionData.state_deal"></td>
        <td v-if="!isShowState">
            <button type="button" v-on:click="toggleShowState" class="xe-btn">수정</button>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "RowComponent",
        props: [
            'optionData'
        ],
        data() {
            return {
                isShowState : true
            }
        },
        methods: {
            toggleShowState : function () {
                this.isShowState = !this.isShowState;
            }
        },
        mounted() {

        }
    }
</script>

<style scoped>

</style>
