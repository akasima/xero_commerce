{{\App\Facades\XeFrontend::js('https://unpkg.com/vue@2.5.21/dist/vue.min.js')->load()}}
{{\App\Facades\XeFrontend::js('https://unpkg.com/vue-star-rating/dist/star-rating.min.js')->load()}}
<div id="{{$id}}">
    <star-rating
        v-model="star"
        :star-size="{{$size}}"
        :read-only="{{($mode==='read')?'true':'false'}}"
        :increment="0.5"
        :show-rating="false"
    ></star-rating>
    @if(isset($name))
        <input type="hidden" name="{{$name}}" :value="star">
    @endif
</div>
<script>
    $(function(){

        Vue.component('star-rating', VueStarRating.default);
        var a= new Vue({
            el:'#{{$id}}',
            data:{
                star: {{$star}}
            }
        })
    });
</script>
