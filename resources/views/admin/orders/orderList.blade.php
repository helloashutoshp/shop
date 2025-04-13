@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        @include('admin.message')
                        <div class="card-title">
                            <button type="button" onclick="window.location.href ='{{ route('admin-order-list') }}'"
                                class="btn btn-default">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order#</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin-order-detail', ['id' => $order->id]) }}"
                                                class="text-info">{{ $order->id }}</a></td>
                                        <td>{{ $order->firstName }} {{ $order->lastName }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->mobile }}</td>
                                        <td>
                                            @if ($order->delivery_status == 'delivered')
                                                <span class="badge bg-success">{{ $order->delivery_status }}</span>
                                            @elseif($order->delivery_status == 'pending')
                                                <span class="badge bg-danger">{{ $order->delivery_status }}</span>
                                            @else
                                                <span class="badge bg-info">{{ $order->delivery_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->grandTotal, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('j M, Y') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->appends(request()->query())->links() }}

                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection
