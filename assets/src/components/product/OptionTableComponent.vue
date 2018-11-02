<template>
    <table class="xe-table">
        <thead>
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
            <row-component v-for="(option,key) in optionList" v-bind:optionData="option" :key="key" @save="save" @remove="remove"></row-component>
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
            'options', 'saveUrl', 'removeUrl', 'loadUrl'
        ],
        data () {
            return{
                optionList: this.options
            }
        },
        methods: {
            save (item) {
                item._token=$('input[name=_token]').val()
                $.ajax({
                    url: this.saveUrl,
                    method: 'post',
                    data: item
                }).done(()=>{
                    this.load()
                })
            },
            remove (item) {
                if(item.id){
                    item._token=$('input[name=_token]').val()
                    $.ajax({
                        url: this.removeUrl,
                        method: 'post',
                        data: item
                    }).done(()=>{
                        this.load()
                    })
                }
            },
            load () {
                $.ajax({
                    url: this.loadUrl,
                    method: 'get'
                }).done(res=>{
                    this.optionList = res
                })
            }
        }
    }
</script>

<style scoped>

</style>
