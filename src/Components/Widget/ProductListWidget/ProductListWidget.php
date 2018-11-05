<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Widget\ProductListWidget;

use Xpressengine\Plugins\XeroCommerce\Services\ProductService;
use Xpressengine\Widget\AbstractWidget;

class ProductListWidget extends AbstractWidget
{
    protected static $path = 'xero_commerce/src/Components/Widget/ProductListWidget/views';

    protected $productService;

    public function __construct(array $config = null)
    {
        $this->productService = new ProductService();

        parent::__construct($config);
    }

    public function render()
    {
        $products = $this->productService->getProductsForWidget(request());

        return $this->renderSkin([
            'products' => $products
        ]);
    }
}
