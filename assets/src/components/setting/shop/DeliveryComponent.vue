<template>
    <div>
        <div v-if="options.length===0">
            <div class="alert alert-danger">
                최소 하나 이상의 배송사 등록이 되어야 정상적으로 상품등록이 가능합니다...!
            </div>
        </div>
        <table class="xe-table">
            <thead>
            <tr>
                <th>회사명 <i class="xi-plus" @click="add"></i></th>
                <th>배송금액</th>
                <th>관리</th>
            </tr>
            </thead>

            <tbody id="deliveryTbody">
            <tr v-for="(option, key) in options">
                <td v-if="edits.indexOf(key) === -1">{{ option.name }}</td>
                <td v-if="edits.indexOf(key) === -1">{{ option.pivot.delivery_fare }}</td>
                <td v-if="edits.indexOf(key) === -1">
                    <button type="button" v-on:click="toggleShowState(key)" class="xe-btn">수정</button>
                    <button type="button" v-on:click="remove(option)" class="xe-btn">삭제</button>
                </td>

                <td v-if="edits.indexOf(key) !== -1">
                    <select v-model="option.id" class="form-control">
                        <option v-for="delivery in list" :value="delivery.id">{{delivery.name}}</option>
                    </select>
                </td>
                <td v-if="edits.indexOf(key) !== -1"><input type="text" v-model="option.pivot.delivery_fare"></td>
                <td v-if="edits.indexOf(key) !== -1">
                    <button type="button" v-on:click="remove(option)" class="xe-btn">삭제</button>
                    <button type="button" v-on:click="save(option)" class="xe-btn">저장</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "DeliveryComponent",
        props: [
          "list", "loadUrl", "addUrl", "removeUrl", "delivery"
        ],
        data () {
            return {
                edits:[],
                options: []
            }
        },
        methods: {
            toggleShowState (key) {
                var index = this.edits.findIndex(v=>{return v===key});
                if(index!==-1){
                    this.edits.splice(index, 1);
                }else {
                    this.edits.push(key)
                }
            },
            add () {
                console.log('add')
                this.options.push({
                    name:'',
                    id:'',
                    pivot:{
                        delivery_fare:'',
                        id:''
                    }
                })
                console.log(this.options)
                this.edits.push(this.options.length-1);
            },
            remove (option) {
                $("#deliveryTbody button").addClass("disabled")
                option._token=document.getElementById('csrf_token').value
                $.ajax({
                    url: this.removeUrl,
                    method:'post',
                    data: option
                }).done(res=>{
                    this.load()
                    $("#deliveryTbody button").removeClass("disabled")
                })
            },
            save (option) {
                $("#deliveryTbody button").addClass("disabled")
                option._token=document.getElementById('csrf_token').value
                $.ajax({
                    url: this.addUrl,
                    method:'post',
                    data: option
                }).done(res=>{
                    this.load()
                    $("#deliveryTbody button").removeClass("disabled")
                })
            },
            load () {
                $.ajax({
                    url: this.loadUrl
                }).done(res=>{
                    this.options=res
                    this.edits=[]
                }).fail(()=>{
                    this.edits=[]
                })
            }
        },
        mounted () {
            this.options= this.delivery
        }
    }
</script>

<style scoped>

</style>
