<template>
    <div class="form-group">
        <label>{{label}}</label>
        <input type="text" class="form-control" v-model="keyword">
        <div>
            <ul class="list-group">
                <li v-for="item in list" class="list-group-item" @click="add(item)">
                    {{item.display_name}} ({{item.email}})
                </li>
            </ul>
        </div>
        <div>
            <ul class="list-group">
                <li v-for="item in choose" class="list-group-item">
                    {{item.display_name}} ({{item.email}})
                    <input type="hidden" :name="name+'[]'" :value="item.id">
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserSearchComponent",
        props: [
            'label', 'description', 'name', 'url', 'value'
        ],
        watch:{
            keyword (el) {
                console.log(el)
                if(el!==''){
                    this.search()
                }else{
                    this.list=[]
                }
            }
        },
        data () {
            return {
                keyword: '',
                list: [],
                choose:[]
            }
        },
        methods: {
            search () {
                    this.list=[]
                $.ajax({
                    url: this.url + '/' + encodeURI(this.keyword)
                }).done(res=>{
                    this.list= res
                })
            },
            add (item) {
                if (typeof this.choose.find(v=>{return v.id === item.id}) === 'undefined') {
                    this.choose.push(item)
                }
                this.keyword = ''
                this.list = []
            }
        },
        mounted () {
            console.log(this.value)
            if(typeof this.value !=='undefined')
            {
                this.choose = this.value
            }
        }
    }
</script>

<style scoped>

</style>
