{{--TODO 게시판 파일 참조 수정--}}
@if ($scriptInit)
    {{ XeFrontend::js('plugins/board/assets/js/BoardTags.js')->appendTo('body')->load() }}
@endif

<div id="{{ $id }}" class="{{ $class }}" data-tags="{{ $strTags }}" data-url="{{$url}}">
    <vue-tags-input v-model="tag" :tags="tags" @tags-changed="update" :autocomplete-items="autocompleteItems" placeholder="{{ $placeholder }}"></vue-tags-input>
</div>

<style>
    .__xe-board-tag .vue-tags-input {
        max-width: 100%;
    }
</style>
