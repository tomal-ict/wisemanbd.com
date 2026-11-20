<div class="category-mega-menu">
    <div class="category-mega-menu-links">
        @foreach (\App\Utility\CategoryUtility::get_immediate_children($category->id) as $first_level_category)
            <div class="category-mega-menu-link-group">
                <a class="category-mega-menu-parent" href="{{ route('products.category', $first_level_category->slug) }}">{{ $first_level_category->getTranslation('name') }}</a>
                @foreach (\App\Utility\CategoryUtility::get_immediate_children($first_level_category->id) as $second_level_category)
                    <a class="category-mega-menu-child" href="{{ route('products.category', $second_level_category->slug) }}">{{ $second_level_category->getTranslation('name') }}</a>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="category-mega-menu-products">
        <div class="category-mega-menu-heading">
            <a href="{{ route('products.category', $category->slug) }}">{{ $category->getTranslation('name') }}</a>
        </div>
        @if ($products->isNotEmpty())
            <div class="category-product-slides" data-interval="5000">
                @foreach ($products->chunk(4) as $product_group)
                    <div class="category-product-slide{{ $loop->first ? ' active' : '' }}">
                        @foreach ($product_group as $product)
                            <a class="category-product-card" href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                <span>{{ $product->getTranslation('name') }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <div class="category-mega-menu-empty">{{ translate('No products found') }}</div>
        @endif
    </div>
</div>
