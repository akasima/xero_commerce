{{\App\Facades\XeFrontend::js('https://unpkg.com/vue@2.5.21/dist/vue.min.js')->load()}}
{{\App\Facades\XeFrontend::js('https://unpkg.com/vue-star-rating/dist/star-rating.min.js')->load()}}
<table class="table">
    <thead>
    <tr>
        <th style="width:10%">번호</th>
        <th style="width:60%">제목</th>
        <th style="width:10%">작성자</th>
        <th style="width:20%">작성일</th>
    </tr>
    </thead>
    <tbody id="feedbackList">
    <template v-for="(item, key) in list" v-if="listExist">
        <tr>
            <td><small>@{{list.length-key}}</small></td>
            <td>
                <small>
                    <star-rating
                        v-model="item.score/2"
                        :star-size="15"
                        :read-only="true"
                        :show-rating="false"
                        :increment="0.5">
                    </star-rating>
                    @{{item.title}}
                </small>
            </td>
            <td><small>@{{item.writer}}</small></td>
            <td><small>@{{item.date}}</small></td>
        </tr>
        <tr>
            <td colspan="4" style="padding:35px">
                @{{item.content}}
            </td>
        </tr>
    </template>
    <tr v-if="!listExist">
        <td colspan="4" style="text-align: center;">상품후기가 존재하지 않습니다.</td>
    </tr>
    </tbody>
</table>
<div style="padding:10px" id="feedbackForm">
    <div class="form-group">
        <label class="sr-only">평점</label>
        {!! uio(\Xpressengine\Plugins\XeroCommerce\Components\UIObjects\Star\StarUIObject::getId(), [
        'id'=>'newFeedback',
        'name'=>'score',
        'star'=>5,
        'size'=>20]) !!}
        <label>후기 작성</label>
        <input type="text" class="form-control" placeholder="제목" name="title">
        <textarea class="form-control" placeholder="후기를 남겨주세요." style="resize:vertical" rows="4" name="content"></textarea>
    </div>
    <div style="text-align: right;">
        <button class="xe-btn xe-btn-black" type="button" onclick="feedback_write()">글쓰기</button>
    </div>
</div>
<script>
    var feedback;
    $(function(){
        feedback=new Vue({
            el: "#feedbackList",
            computed: {
                listExist: function () {
                    return this.list.length> 0;
                }
            },
            data: function () {
                return {
                    list: {!! json_encode($list) !!}
                }
            },
            methods: {
                load: function () {
                    $.ajax({
                        url: "{{route('xero_commerce::product.feedback.get',['product'=>$product])}}"
                    }).done(function(res){
                        this.list = res
                        console.log(res)
                    }.bind(this))
                },
                write: function (data) {
                    this.add(data, "{{route('xero_commerce::product.feedback.add',['product'=>$product])}}")
                },
                add: function (data, url) {

                    if ({{\Illuminate\Support\Facades\Auth::check()?'true':'false'}}) {
                        data._token = "{{csrf_token()}}"
                        $.ajax({
                            url: url,
                            method: 'post',
                            data: data
                        }).done(function(){
                            document.location.href=document.location.href+"/#feedbackForm";
                        }.bind(this))
                    }else{
                        XE.toast('warning','로그인 후 사용할 수 있습니다')
                    }
                }
            }
        })
    })
    function feedback_write(){
        var data={
            title: $("#feedbackForm [name=title]").val(),
            content:$("#feedbackForm [name=content]").val(),
            score: Number($("#feedbackForm [name=score]").val())*2,
        }
        feedback.write(data);
    }

</script>
