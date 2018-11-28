{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/EventWidget/Skins/Common/assets/style.css')->load() }}

<section class="section-event">
 	<div class="inner-main">
        <h2 class="title-event">EVENT</h2>
        <ul class="list-event">
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['product_id_1']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['product_id_1']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['product_id_1']->name }}</strong>
                        <span class="info">{{ $products['product_id_1']->sub_name }}</span>
                    </div>
                </a>
            </li>
			<li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['product_id_2']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['product_id_2']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['product_id_2']->name }}</strong>
                        <span class="info">{{ $products['product_id_2']->sub_name }}</span>
                    </div>
                </a>
            </li>
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['product_id_3']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['product_id_3']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['product_id_3']->name }}</strong>
                        <span class="info">{{ $products['product_id_3']->sub_name }}</span>
                    </div>
                </a>
            </li>
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['product_id_4']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['product_id_4']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['product_id_4']->name }}</strong>
                        <span class="info">{{ $products['product_id_4']->sub_name }}</span>
                    </div>
                </a>
            </li>
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['product_id_5']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['product_id_5']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['product_id_5']->name }}</strong>
                        <span class="info">{{ $products['product_id_5']->sub_name }}</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</section>
