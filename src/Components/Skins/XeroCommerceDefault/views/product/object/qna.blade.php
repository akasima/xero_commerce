{{\App\Facades\XeFrontend::js('https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js')->load()}}
<div id="qna">
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
        <template v-for="(item, key) in list" v-if="listExist">
            <tr>
                <td><small>@{{list.length-key}}</small></td>
                <td><small>@{{item.title}}</small></td>
                <td><small>@{{item.writer}}</small></td>
                <td><small>@{{item.date}}</small></td>
            </tr>
            <tr>
                <td colspan="4" style="padding:35px">
                    @{{item.content}}
                    <p style="text-align: right" v-if="item.grant">
                        <small style="cursor: pointer;" @click="childDocument[key].display=!childDocument[key].display">답글(@{{item.children.length}})</small>
                        /
                        <small style="cursor: pointer;" @click="childDocument[key].shown=!childDocument[key].shown">쓰기</small>
                    </p>
                </td>
            </tr>
            <tr style="border-bottom: 2px #ddd solid" v-show="childDocument[key].display">
                <td colspan="4" style="text-align: right">
                    <div v-for="child in item.children" style="text-align: left; padding:10px">
                        <h5>@{{child.writer}} <small>(@{{child.date}})</small></h5>
                        <p>@{{child.content}}</p>
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
<script>
    $(function(){
        var qna = new Vue({
            el: "#qna",
            computed: {
                listExist: function () {
                    return this.list.length> 0;
                }
            },
            watch: {
                list: function (el) {
                    this.childDocument=[]
                    el.forEach(function(v){
                        this.childDocument.push({
                            title: '-',
                            content: '',
                            shown: false,
                            display: false
                        })
                    }.bind(this))
                }
            },
            data: function () {
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
                load: function () {
                    $.ajax({
                        url: "{{route('xero_commerce::product.qna.get',['product'=>$product])}}"
                    }).done(function (res){
                        this.list = res
                    }.bind(this))
                },
                write: function () {
                    this.add(this.document, "{{route('xero_commerce::product.qna.add',['product'=>$product])}}")
                },
                erase: function (data) {
                    data.title =''
                    data.content=''
                },
                answer: function (id, data) {
                    this.add(data, "{{route('xero_commerce::qna.answer',['qna'=>''])}}"+'/'+id)
                },
                add: function (data, url) {

                    if ({{\Illuminate\Support\Facades\Auth::check()?'true':'false'}}) {
                        data._token = $("#csrf_token").val()
                        data.privacy = Number(data.privacy)
                        $.ajax({
                            url: url,
                            method: 'post',
                            data: data
                        }).done(function(){
                            this.load()
                            this.erase(data)
                        }.bind(this))
                    }else{
                        XE.toast('warning','로그인 후 사용할 수 있습니다')
                    }
                }
            },
            mounted: function() {
                this.list = {!! json_encode($list) !!}
            }
        });
    })
</script>
