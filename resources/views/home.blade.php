@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="{{ route('products.index') }}" class="text-decoration-none">
                <div class="card text-white bg-primary mb-3 shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <h2 class="card-text fw-bold">{{ $totalProducts }}</h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('customers.index') }}" class="text-decoration-none">
                <div class="card text-white bg-success mb-3 shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Customers</h5>
                        <h2 class="card-text fw-bold">{{ $totalCustomers }}</h2>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('orders.index') }}" class="text-decoration-none">
                <div class="card text-white bg-secondary mb-3 shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales <sup>( Pending | Completed )</sup></h5>
                        <h2 class="card-text fw-bold">₹ {{ number_format($totalSales, 2) }}</h2>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white pt-3 pb-2 border-bottom-0">
                    <h5 class="mb-0 fw-bold">Orders by Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($orderStatuses as $statusCount)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('orders.index', ['status' => $statusCount->status]) }}"
                                    class="text-decoration-none">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body text-center py-4">
                                            <h6 class="text-uppercase text-muted fw-bold mb-2">{{ $statusCount->status }}
                                            </h6>
                                            <h3 class="mb-0 text-primary fw-bold">{{ $statusCount->total }}</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        @if ($orderStatuses->isEmpty())
                            <div class="col-12 text-center text-muted py-4">
                                <p class="mb-0 text-secondary">No orders found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
