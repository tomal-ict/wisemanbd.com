@if(get_setting('home_categories') != null) 
    @php $home_categories = json_decode(get_setting('home_categories'), true) ?: []; @endphp
    @foreach ($home_categories as $key => $value)
        @if ($value === 'new_arrivals')
            @include('frontend.partials.backupfeatured_products_section')
        @elseif ($value === 'best_selling')
            @include('frontend.partials.best_selling_section')
        @else
            @php $category = \App\Models\Category::find($value); @endphp
            @if ($category != null)
                <section class="mb-4">
                    <div class="container">
                        <div class="py-4 py-md-3">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ $category->getTranslation('name') }}</span>
                                </h3>
                                <a href="{{ route('products.category', $category->slug) }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View More') }}</a>
                            </div>
                            <div class="row">
                                @foreach (get_cached_products($category->id) as $key => $product)
                                    <div class="col-6 col-md-3 col-lg-5th">
                                        @include('frontend.partials.product_box_1',['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endif
    @endforeach
@endif
