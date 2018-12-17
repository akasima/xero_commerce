<div class="form-group">
    <label>좌측 상품 ID</label>
    <input class="form-control" name="product_id_1" onclick="toggle_modal(1)" value="{{ array_get($args, 'product_id_1') }}" readonly>
</div>

<div class="form-group">
    <label>우측 상단 첫번째 상품 ID</label>
    <input class="form-control" name="product_id_2" onclick="toggle_modal(2)" value="{{ array_get($args, 'product_id_2') }}" readonly>
</div>

<div class="form-group">
    <label>우측 상단 두번째 상품 ID</label>
    <input class="form-control" name="product_id_3" onclick="toggle_modal(3)" value="{{ array_get($args, 'product_id_3') }}" readonly>
</div>

<div class="form-group">
    <label>우측 하단 첫번째 상품 ID</label>
    <input class="form-control" name="product_id_4" onclick="toggle_modal(4)" value="{{ array_get($args, 'product_id_4') }}" readonly>
</div>

<div class="form-group">
    <label>우측 하단 두번째 상품 ID</label>
    <input class="form-control" name="product_id_5" onclick="toggle_modal(5)" value="{{ array_get($args, 'product_id_5') }}" readonly>
</div>
<div id="product-modal-base" class="product-modal-toggle">
    <div class="product-modal">
        <button type="button" onclick="toggle_modal()">닫기</button>
        <div class="row">
            <div class-="col-sm-12">
                <input type="hidden" name="target_id" value="1">
                <ul class="product-list">
                    @foreach(\Xpressengine\Plugins\XeroCommerce\Models\Product::all() as $product)
                        <li class="product-item">
                            <a id="product_{{$product->id}}" href="#" style="margin:0 12px" onclick="event.preventDefault();select_product(this, {{$product->id}})">
                                <span class="thumbnail" style="background-image: url({{$product->getThumbnailSrc()}})"></span>
                                <div>
                                    {{$product->getName()}}
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
    function toggle_modal(value)
    {
        $("#product-modal-base").toggleClass("product-modal-toggle")
        $("[name=target_id]").val(value);
    }

    function select_product(target, value)
    {
        $(".product-item a").removeClass('select');
        $(target).addClass("select");
        var num = $("[name=target_id]").val();
        $("[name=product_id_"+num+"]").val(value);
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
