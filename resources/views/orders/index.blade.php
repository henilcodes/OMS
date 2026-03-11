@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Orders</h2>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Create New Order</a>
    </div>

    <div id="filterSection">
        <div class="card mb-4">
            <div class="card-body bg-light">
                <form method="GET" action="{{ route('orders.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="code" class="form-label">Order Code</label>
                        <input type="text" name="code" id="code" class="form-control"
                            value="{{ request('code') }}" placeholder="Search by code...">
                    </div>

                    <div class="col-md-2">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                            value="{{ request('customer_name') }}" placeholder="Search by name...">
                    </div>

                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Customer Name</th>
                            <th>Total Items</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="align-middle">{{ $order->code }}</td>
                                <td class="align-middle">{{ $order->customer->name }}</td>
                                <td class="align-middle">{{ $order->order_items_count }}</td>
                                <td class="align-middle">
                                    @if ($order->status === 'pending')
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="form-select form-select-sm d-inline-block w-auto">
                                                <option value="pending"
                                                    {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="completed"
                                                    {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                                </option>
                                                <option value="cancelled"
                                                    {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span
                                            class="badge rounded-pill {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">₹ {{ number_format($order->total_amount, 2) }}</td>
                                <td class="align-middle">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td class="align-middle">

                                    <a href="{{ route('orders.show', $order) }}"
                                        class="btn btn-primary btn-sm ms-2">Show</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
@endsection
