
<script src="{{Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')}}"></script>

{{--<div id="component-container" class="form-group">--}}
    {{--<label>출력할 카테고리 ID 설정</label>--}}
    {{--<category-component :category-items='{{ json_encode($categoryItems) }}'--}}
                        {{--get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"--}}
                        {{--mode="create">--}}
    {{--</category-component>--}}
    {{--<input class="form-control" type="text" name="category_item_id" value="{{ array_get($args, 'category_item_id') }}">--}}
{{--</div>--}}

<div class="form-group">
    <label>출력할 상품 ID 설정</label>
    <a href="#" onclick="event.preventDefault();toggle_modal()">선택하기</a>
    <input class="form-control" type="text" readonly name="product_id" value="{{ array_get($args, 'product_id') }}" onclick="toggle_modal()">
</div>

<div id="product-modal-base" class="product-modal-toggle">
    <div class="product-modal">
        <button type="button" onclick="toggle_modal()">닫기</button>
        <div class="row">
            <div class-="col-sm-12">
                <ul class="product-list">
                    @foreach(\Xpressengine\Plugins\XeroCommerce\Models\Product::all() as $product)
                        <li class="product-item">
                            <a id="product_{{$product->id}}" href="#" style="margin:0 12px" onclick="event.preventDefault();select_product(this, {{$product->id}})">
                                <span class="thumbnail" style="background-image: url({{$product->getThumbnailSrc()}})"></span>
                                <div>
                                    {{$product->name}}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    var select = [];
    function toggle_modal()
    {
        $("#product-modal-base").toggleClass("product-modal-toggle")
    }

    function select_product(target, value)
    {
        $(target).toggleClass("select");
        if($(target).hasClass("select")){
            select.push(value)
        }else{
            var key=select.indexOf(value);
            select.splice(key,1);
        }
        $("[name=product_id]").val(select.join(','));
        console.log(select)
    }
    $(function(){
       var selected_value = $("[name=product_id]").val();
       var selected_array = selected_value.split(',');
       selected_array.forEach(function(v){
           $("#product_"+v).addClass("select")
           select.push(Number(v))
       });
    });
</script>
<style>
    #product-modal-base{
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 20;
        background: rgba(0,0,0,0.1);
    }
    #product-modal-base .product-modal {
        width:600px;
        height:600px;
        margin:0 auto;
        background: white;
        margin-top:30px;
        overflow-y: scroll;
    }
    .product-modal-toggle{
        display:none;
    }
    .product-list{
        display: flex;
        list-style: none;
        flex-wrap: wrap;
        margin:0 10px;
    }
    .product-item{
        float:left;
        width:33%;
        margin-bottom: 10px;
    }
    .product-item .thumbnail{
        display: block;
        width: 100%;
        padding-top: 100%;
        background-size: cover;
        background-position: 50% 50%;
    }
    .product-item .select .thumbnail{
        opacity: 0.5;
    }
</style>
