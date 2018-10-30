<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\UIObjects\Tag;

use Xpressengine\UIObject\AbstractUIObject;

class TagUIObject extends AbstractUIObject
{
    protected static $id = 'uiobject/xero_commerce@tag';

    protected static $loaded = false;

    public function render()
    {
        $args = $this->arguments;

        if (empty($args['tags'])) {
            $args['tags'] = [];
        }

        $args['strTags'] = '';
        if (is_array($args['tags']) && count($args['tags']) > 0) {
            $tagWords = [];
            foreach ($args['tags'] as $tag) {
                $tagWords[] = $tag['word'];
            }
            $args['strTags'] = sprintf('["%s"]', implode('","', $tagWords));
        }

        $args['id'] = 1;

        if (empty($args['class'])) {
            //TODO 게시판 스타일 수정
            $args['class'] = 'xe-select-label __xe-board-tag';
        }

        if (empty($args['placeholder'])) {
            $args['placeholder'] = '태그를 입력하세요.';
        }

        if (empty($args['url'])) {
            $args['url'] = '/editor/hashTag';
        }

        $args['scriptInit'] = false;
        if (self::$loaded === false) {
            self::$loaded = true;

            $args['scriptInit'] = true;
        }

        return \View::make('xero_commerce::src/Components/UIObjects/Tag/views/tag', $args)->render();
    }
}
