<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 12/12/2018
 * Time: 6:39 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Handlers;


use Illuminate\Database\Eloquent\Model;
use Xpressengine\Plugins\XeroCommerce\Models\FeedBack;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Qna;

class CommunicationHandler
{

    public function getList(Model $model)
    {
        return $model->where('type_type',Product::class)->latest()->get();
    }

    public function getItem(Model $model, $id)
    {
        return $model->find($id);
    }

    public function getTypeModel($type)
    {
        switch ($type)
        {
            case 'feedback':
                return new FeedBack();
                break;
            case 'qna':
                return new Qna();
                break;
            default:
                abort(500, '해당 정보를 불러 올 수 없습니다.');
                break;
        }
    }

    public function getTargetInfo(Model $model)
    {
        if(is_a($model, Product::class)){
            return $this->getProductInfo($model);
        }else{
            abort(500, '해당 정보를 불러 올 수 없습니다.');
        }
    }

    private function getProductInfo(Product $product)
    {

        return (object)[
            'name'=>$product->name,
            'sell_price'=>number_format($product->sell_price),
            'original_price'=>number_format($product->oirinal_price),
            'stock'=>$product->getStock(),
            'score'=>$this->getProductScore($product),
            'feedbacks'=>$product->feedback()->latest()->limit(3)->get(),
            'qnas'=>$product->qna()->latest()->limit(3)->get()
        ];
    }

    public function getProductScore(Product $product)
    {
        return $product->feedback()->avg('score');
    }
}
