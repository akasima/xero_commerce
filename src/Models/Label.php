<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Label extends DynamicModel
{
    protected $table = 'xero_commerce_label';

    public $timestamps = false;

    protected $fillable = ['name', 'eng_name', 'background_color', 'text_color'];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'xero_commerce_product_label', 'label_id', 'product_id');
    }

    public function render()
    {
        XeFrontend::html('label')->content('
            <style>
                
            .xe-shop-tag {
                display: inline-block;
                padding: 2px 4px;
                background-color: #ddd;
                font-size: 9px;
                color: #333;
                text-transform: uppercase;
                transform: translateY(-1px);
            }
            </style>
        
        ')->load();

        $html = '<span class="xe-shop-tag" ';
        if($this->background_color && $this->text_color) $html .='style="background: '.$this->background_color.'; color:'.$this->text_color.'"';
        $html.='>'.$this->name.'</span>';
        return $html;
    }
}
