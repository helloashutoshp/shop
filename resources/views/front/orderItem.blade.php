@extends('front.layout.layout')
@section('title')
    Profile
@endsection
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.layout.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                        </div>

                        <div class="card-body pb-0">
                            <div class="card card-sm">
                                <div class="card-body bg-light mb-3">
                                    <div class="row">
                                        <div class="col-6 col-lg-3">
                                            <h6 class="heading-xxxs text-muted">Order No:</h6>
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                {{ $orderItem->id }}
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                <time datetime="2019-10-01">
                                                    01 Oct, 2019
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <h6 class="heading-xxxs text-muted">Status:</h6>
                                            @if ($orderItem->delivery_status == 'delivered')
                                                <span class="badge bg-success">{{ $orderItem->delivery_status }}</span>
                                            @elseif($orderItem->delivery_status == 'pending')
                                                <span class="badge bg-danger">{{ $orderItem->delivery_status }}</span>
                                            @else
                                                <span class="badge bg-info">{{ $orderItem->delivery_status }}</span>
                                            @endif
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                                            <p class="mb-0 fs-sm fw-bold">
                                                ${{ number_format($orderItem->grandTotal, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-3">
                            <h6 class="mb-7 h5 mt-4">Order Items ({{$count}})</h6>
                            <hr class="my-3">
                            <ul>
                                @foreach ($items as $item)
                                    @php
                                        $image = getProductImg($item->product_id);
                                    @endphp
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-4 col-md-3 col-xl-2">
                                                @if ($image)
                                                    <a href="" class="product-img"><img
                                                            class="img-fluid"
                                                            src="{{ asset('uploads/product/large/' . $image->image) }}"
                                                            alt=""></a>
                                                @else
                                                    <a href="" class="product-img">

                                                        <img class="img-fluid"
                                                            src="{{ asset('uploads/product/large/404.jpg') }}"
                                                            alt="">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <p class="mb-4 fs-sm fw-bold">
                                                    <a class="text-body" href="product.html">{{ $item->name }}</a>
                                                    <br>
                                                    <small class="text-muted">${{number_format($item->price,2) }} X {{ $item->qty }} =
                                                        $
                                                        {{ number_format($item->total, 2) }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card card-lg mb-5 mt-3">
                        <div class="card-body">
                            <h6 class="mt-0 mb-3 h5">Order Total</h6>
                            <ul>
                                <li class="list-group-item d-flex">
                                    <span>Subtotal</span>
                                    <span class="ms-auto">${{ number_format($orderItem->subtotal, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Discount</span>
                                    <span class="ms-auto">-${{ number_format($orderItem->discount, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Shipping</span>
                                    <span class="ms-auto">+${{ number_format($orderItem->shipping, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex fs-lg fw-bold">
                                    <span>Total</span>
                                    <span class="ms-auto">${{ number_format($orderItem->grandTotal, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
