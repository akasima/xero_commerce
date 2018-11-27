<template>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th style="width:10%">번호</th>
                <th style="width:60%">제목</th>
                <th style="width:10%">작성자</th>
                <th style="width:20%">작성일</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(item, key) in reverseList" v-if="listExist">
                <tr>
                    <td><small>{{list.length-key}}</small></td>
                    <td><small>{{item.title}}({{item.score}})</small></td>
                    <td><small>{{item.writer}}</small></td>
                    <td><small>{{item.date}}</small></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding:35px">
                        {{item.content}}
                    </td>
                </tr>
            </template>
            <tr v-if="!listExist">
                <td colspan="4" style="text-align: center;">상품후기가 존재하지 않습니다.</td>
            </tr>
            </tbody>
        </table>
        <div style="padding:10px">
            <div class="form-group">
                <label class="sr-only">평점</label>
                <select v-model="document.score">
                    <option v-for="n in 10">
                        {{n}}
                    </option>
                </select>
                <label>후기 작성</label>
                <input type="text" class="form-control" placeholder="제목" v-model="document.title">
                <textarea class="form-control" placeholder="후기를 남겨주세요." style="resize:vertical" rows="4" v-model="document.content"></textarea>
            </div>
            <div style="text-align: right;">
                <button class="xe-btn xe-btn-black" @click="write">글쓰기</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductFeedBackComponent",
        props: ['defaultList','productId', 'feedbackGetUrl', 'feedbackAddUrl', 'auth'],
        computed: {
            listExist () {
                return this.list.length> 0;
            },
            reverseList () {
                var reverseList = [];
                var length = this.list.length;
                this.list.forEach((v, k)=>{
                    reverseList.push(this.list[length-k-1])
                })
                return reverseList
            }
        },
        data () {
            return {
                document: {
                    title: '',
                    content: '',
                    privacy: false,
                    score:10
                },
                list: []
            }
        },
        methods: {
            load () {
                $.ajax({
                    url: this.feedbackGetUrl
                }).done(res=>{
                    this.list = res
                    console.log(res)
                })
            },
            write () {
                this.add(this.document, this.feedbackAddUrl)
            },
            erase (data) {
                data.title =''
                data.content=''
                data.score=10
            },
            add (data, url) {

                if (this.auth) {
                    data._token = $("#csrf_token").val()
                    data.privacy = Number(data.privacy)
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: data
                    }).done(()=>{
                        this.load()
                        this.erase(data)
                    })
                }else{
                    XE.toast('warning','로그인 후 사용할 수 있습니다')
                }
            }
        },
        mounted () {
            console.log(this.defaultList)
            this.list = this.defaultList
        }
    }
</script>

<style scoped>

</style>
