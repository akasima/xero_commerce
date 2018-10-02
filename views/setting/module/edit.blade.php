{{ XeFrontend::js($plugin::asset('assets/js/module_category.js'))->load() }}

<div>
    <div class="clearfix">
        <label>리스트에 표시할 카테고리를 선택하세요.
            <small class="categoryItemId xe-category-navigator"> </small>
        </label>
    </div>

    <div class="xe-dropdown __xe-dropdown-form xe-rich-shop-category" data-relative="categoryItemId">
        <input type="hidden" class="categoryItemId" name="categoryItemId" value="" />
        <input type="hidden" class="categoryItemId-depth" name="categoryItemDepth" value="" />
        <button class="xe-btn select" type="button" data-toggle="xe-dropdown" aria-expanded="false">선택</button>
        <ul class="xe-dropdown-menu">
            @foreach($categoryItems as $item)
                <li><a href="#" data-url="{{route('manage.category.edit.item.children', ['id' => $item->category_id])}}" data-id="{{$item->id}}">{{xe_trans($item->word)}}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="xe-category-children categoryItemId"></div>
</div>
