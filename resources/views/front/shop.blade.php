@extends('front.layout.layout')
@section('title')
    Shop
@endsection
@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @if ($category->isNotEmpty())
                                <div class="accordion accordion-flush" id="accordionExample">
                                    @foreach ($category as $key => $cate)
                                        @if ($cate->subCategoryStatus->isNotEmpty())
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne{{ $key }}"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                        {{ $cate->name }}
                                                    </button>
                                                </h2>
                                                <div id="collapseOne{{ $key }}"
                                                    class="accordion-collapse collapse {{ $categorySelected == $cate->id ? 'show' : '' }}"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                    style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @foreach ($cate->subCategoryStatus as $subcate)
                                                                <a href="{{ route('shop', [$cate->slug, $subcate->slug]) }}"
                                                                    class="nav-item nav-link {{ $subCategorySelected == $subcate->id ? 'text-primary' : '' }}">{{ $subcate->name }}</a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('shop', $cate->slug) }}"
                                                class="nav-item nav-link {{ $categorySelected == $cate->id ? 'text-primary' : '' }}">
                                                {{ $cate->name }}</a>
                                        @endif
                                    @endforeach

                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>

                    <div class="card">
                        @if ($brand->isNotEmpty())
                            <div class="card-body">
                                @foreach ($brand as $brandd)
                                    <div class="form-check mb-2">
                                        <input {{ in_array($brandd->id, $brandValue) ? 'checked' : '' }}
                                            class="form-check-input brand-val" type="checkbox" value="{{ $brandd->id }}"
                                            id="flexCheckDefault{{ $brandd->id }}" name="brandName[]">
                                        <label class="form-check-label" for="flexCheckDefault{{ $brandd->id }}">
                                            {{ $brandd->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <input type="text" id="example" name="example_name" value="" />
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <select name="price" id="price">
                                        <option {{ $sort == 'latest' ? 'selected' : '' }} value="latest">Latest</option>
                                        <option {{ $sort == 'low' ? 'selected' : '' }} value="low">Price Low to High
                                        </option>
                                        <option {{ $sort == 'high' ? 'selected' : '' }} value="high">Price High to Low
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if ($product->isNotEmpty())
                            @foreach ($product as $prod)
                                @php
                                    $productImage = $prod->product_img->first();
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            @if ($productImage)
                                                <a href="{{ route('product', $prod->slug) }}" class="product-img">
                                                    <img class="card-img-top"
                                                        src="{{ asset('uploads/product/large/' . $productImage->image) }}"
                                                        alt="">
                                                </a>
                                            @else
                                                <a href="{{ route('product', $prod->slug) }}" class="product-img">
                                                    <img class="card-img-top"
                                                        src="{{ asset('uploads/product/large/404.jpg') }}" alt="">
                                                </a>
                                            @endif
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>
                                            <div class="product-action">
                                                <a class="btn btn-dark" href=""
                                                    onClick="addToCart(event,{{ $prod->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link"
                                                href="{{ route('product', $prod->slug) }}">{{ $prod->title }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>{{ $prod->price }}</strong></span>
                                                @if ($prod->price)
                                                    <span
                                                        class="h6 text-underline"><del>{{ $prod->compare_price }}</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="col-md-12 pt-5">
                            {{ $product->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom-js')
    <script>
        $("#example").ionRangeSlider({
            // skin: "big",
            min: 0,
            max: 10000,
            from: {{ $priceTo }},
            step: 10,
            skin: 'round',
            max_postfix: "+",
            to: {{ $priceFrom }},
            type: 'double',
            prefix: "$",
            // grid: true,
            // grid_num: 10,
            onFinish: function() {
                brandChecked();
            }
        });
        var slider = $('#example').data('ionRangeSlider');
        $('.brand-val').change(function() {
            brandChecked();
        })

        $('#price').change(function() {
            brandChecked();
        })

        function brandChecked() {
            var brand = [];
            $('.brand-val').each(function() {
                if ($(this).is(':checked') == true) {
                    brand.push($(this).val());
                }
            });
            var url = '{{ url()->current() }}?';
            url += '&rangeFrom=' + slider.result.from + '&rangeTo=' + slider.result.to;
            if (brand.length > 0) {
                url += '&brands=' + brand.toString();
            }

            var price = $('#price').val();

            url += '&priceRange=' + price;

            window.location.href = url;
        }
    </script>
@endsection
