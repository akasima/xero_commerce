{{--@deprecated since ver 1.1.4--}}
{{ XeFrontend::js($plugin::asset('assets/js/module_category.js'))->load() }}

<div>
    <div class="clearfix">
        <label>모듈에 사용할 라벨을 선택하세요.</label>
    </div>

    <div>
        <input type="hidden" name="labels[]" value="">
        @foreach ($labels as $label)
            <input type="checkbox" name="labels[]" value="{{ $label->id }}" @if (in_array($label->id, $moduleLabels) == true) checked @endif>{{ $label->name }}
        @endforeach
    </div>
</div>

<div>
    <div class="clearfix">
        <label>리스트에 표시할 카테고리를 선택하세요.
            <small class="categoryItemId xe-category-navigator"> </small>
        </label>
    </div>

    <div class="xe-dropdown __xe-dropdown-form xe-rich-shop-category" data-relative="categoryItemId">
        <input type="hidden" class="categoryItemId" name="categoryItemId" value="" />
        <input type="hidden" class="categoryItemId-depth" name="categoryItemDepth" value="" />
        <button class="xe-btn select" type="button" data-toggle="xe-dropdown" aria-expanded="false">{{$selectedLabel}}</button>
        <ul class="xe-dropdown-menu">
            @foreach($categoryItems as $item)
                <li @if($categoryItemId == $item->id) class="on" @endif ><a href="#" data-url="{{route('manage.category.edit.item.children', ['id' => $item->category_id])}}" data-id="{{$item->id}}">{{xe_trans($item->word)}}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="xe-category-children categoryItemId"></div>
</div>
