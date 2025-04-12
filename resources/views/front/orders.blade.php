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
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Orders #</th>
                                            <th>Date Purchased</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($orders->isNotEmpty())
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <a href="{{route('order-detail',[$order->id])}}" class="text-info">{{ $order->id }}</a>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('j M, Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($order->delivery_status == 'delivered')
                                                            <span
                                                                class="badge bg-success">{{ $order->delivery_status }}</span>
                                                        @elseif($order->delivery_status == 'pending')
                                                            <span
                                                                class="badge bg-danger">{{ $order->delivery_status }}</span>
                                                        @else
                                                            <span class="badge bg-info">{{ $order->delivery_status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($order->grandTotal,2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
