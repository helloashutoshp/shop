@extends('front.layout.layout')
@section('title')
    Product
@endsection
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            @include('admin.message')

            <div class="row ">
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        @if ($product->product_img->isNotEmpty())
                            <div class="carousel-inner bg-light">
                                @foreach ($product->product_img as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img class="w-100 h-100" src="{{ asset('uploads/product/large/' . $image->image) }}"
                                            alt="Image">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-image">
                                <img class="w-100 h-100" src="{{ asset('uploads/product/large/404.jpg') }}" alt="Image">
                            </div>
                        @endif
                        @if ($product->product_img->count() > 1)
                            <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                                <i class="fa fa-2x fa-angle-left text-dark"></i>
                            </a>
                            <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                                <i class="fa fa-2x fa-angle-right text-dark"></i>
                            </a>
                        @endif

                    </div>
                </div>
                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            <div class="text-primary mr-2">
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star-half-alt"></small>
                                <small class="far fa-star"></small>
                            </div>
                            <small class="pt-1">(99 Reviews)</small>
                        </div>
                        @if ($product->compare_price)
                            <h2 class="price text-secondary"><del>${{ $product->compare_price }}</del></h2>
                        @endif
                        <h2 class="price ">${{ $product->price }}</h2>
                        {!! $product->short_description !!}

                        <a href="" onClick="addToCart(event,{{ $product->id }})" class="btn btn-dark"><i
                                class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="bg-light">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab" aria-controls="description"
                                    aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                                    type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping &
                                    Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                    type="button" role="tab" aria-controls="reviews"
                                    aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                {!! $product->description !!}
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                {!! $product->shipping_return !!}

                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-5 section-8">
        <div class="container">
            <div class="section-title">
                <h2>Related Products</h2>
            </div>
            @if ($items->isNotEmpty())
                <div class="col-md-12">
                    <div id="related-products" class="carousel related-slick">
                        @foreach ($items as $item)
                            @php
                                $image = $item->product_img->first();
                            @endphp
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    @if ($image)
                                        <a href="{{ route('product', $item->slug) }}" class="product-img"><img
                                                class="card-img-top"
                                                src="{{ asset('uploads/product/large/' . $image->image) }}"
                                                alt=""></a>
                                    @else
                                        <a href="{{ route('product', $item->slug) }}" class="product-img">

                                            <img class="card-img-top" src="{{ asset('uploads/product/large/404.jpg') }}"
                                                alt="">
                                        </a>
                                    @endif
                                    <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        <a class="btn btn-dark" href="" onClick="addToCart(event,{{ $product->id }})">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{ $item->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>${{ $item->price }}</strong></span>
                                        <span class="h6 text-underline"><del>${{ $item->compare_price }}</del></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection
@section('custom-js')
    <script>
        $('.related-slick').slick({
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            // variableWidth: true,
            autoplay: true,
        });

       
    </script>
@endsection
