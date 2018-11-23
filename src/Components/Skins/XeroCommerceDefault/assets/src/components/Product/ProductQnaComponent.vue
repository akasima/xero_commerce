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
                    <td><small>{{item.title}}</small></td>
                    <td><small>{{item.writer}}</small></td>
                    <td><small>{{item.date}}</small></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding:35px">
                        {{item.content}}
                        <p style="text-align: right" v-if="item.grant">
                            <small style="cursor: pointer;" @click="childDocument[key].display=!childDocument[key].display">답글({{item.children.length}})</small>
                            /
                            <small style="cursor: pointer;" @click="childDocument[key].shown=!childDocument[key].shown">쓰기</small>
                        </p>
                    </td>
                </tr>
                <tr style="border-bottom: 2px #ddd solid" v-show="childDocument[key].display">
                    <td colspan="4" style="text-align: right">
                        <div v-for="child in item.children" style="text-align: left; padding:10px">
                            <h5>{{child.writer}} <small>({{child.date}})</small></h5>
                            <p>{{child.content}}</p>
                        </div>
                    </td>
                </tr>
                <tr style="border-bottom: 2px #ddd solid" v-show="childDocument[key].shown">
                    <td colspan="4" style="text-align: right">
                            <div class="form-group">
                                <label>답글</label>
                                <textarea class="form-control" placeholder="답글내용" style="resize:vertical" rows="4" v-model="childDocument[key].content"></textarea>
                            </div>
                            <div style="text-align: right;">
                                <button class="xe-btn xe-btn-black" @click="answer(item.id, childDocument[key])">글쓰기</button>
                            </div>
                    </td>
                </tr>
            </template>
            <tr v-if="!listExist">
                <td colspan="4" style="text-align: center;">QnA가 존재하지 않습니다.</td>
            </tr>
            </tbody>
        </table>
        <div style="padding:10px">
            <div class="form-group">
                <label>Qna작성</label>
                <input type="text" class="form-control" placeholder="제목" v-model="document.title">
                <textarea class="form-control" placeholder="문의내용" style="resize:vertical" rows="4" v-model="document.content"></textarea>
            </div>
            <div style="text-align: right;">
                <input type="checkbox" v-model="document.privacy"> 비공개
                <button class="xe-btn xe-btn-black" @click="write">글쓰기</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductQnaComponent",
        props: ['defaultList','productId', 'qnaGetUrl', 'qnaAddUrl', 'answerUrl'],
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
        watch: {
            list (el) {
                this.childDocument=[]
                el.forEach(v=>{
                    this.childDocument.push({
                        title: '-',
                        content: '',
                        shown: false,
                        display: false
                    })
                })
            }
        },
        data () {
            return {
                document: {
                    title: '',
                    content: '',
                    privacy: false
                },
                list: [],
                childDocument: []
            }
        },
        methods: {
            load () {
                $.ajax({
                    url: this.qnaGetUrl
                }).done(res=>{
                    this.list = res
                })
            },
            write () {
                this.add(this.document, this.qnaAddUrl)
            },
            erase (data) {
                data.title =''
                data.content=''
            },
            answer (id, data) {
                this.add(data, this.answerUrl+'/'+id)
            },
            add (data, url) {
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
