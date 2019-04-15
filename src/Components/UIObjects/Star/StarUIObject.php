<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 13/12/2018
 * Time: 3:24 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Components\UIObjects\Star;

use Xpressengine\UIObject\AbstractUIObject;

class StarUIObject extends AbstractUIObject
{
    protected static $id = 'uiobject/xero_commerce@star';

    public function render()
    {
        $args = $this->arguments;
        $args['id'] = 'star' . $args['id'];
        if (!isset($args['mode'])) {
            $args['mode'] = 'write';
        }
        return \View::make('xero_commerce::src/Components/UIObjects/Star/views/star', $args)->render();
    }
}
