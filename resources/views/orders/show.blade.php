@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Order #{{ $order->code }}</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="row">

        <!-- Order Details -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header fw-bold">Order Details</div>
                <div class="card-body">
                    <p><strong>Order Code:</strong> {{ $order->code }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <strong class="me-2">Status:</strong>

                        @if ($order->status === 'pending')
                            <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')

                                <select name="status" onchange="this.form.submit()"
                                    class="form-select form-select-sm w-auto">

                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>
                            </form>
                        @else
                            <span
                                class="badge rounded-pill {{ $order->status === 'completed'
                                    ? 'bg-success'
                                    : ($order->status === 'cancelled'
                                        ? 'bg-danger'
                                        : 'bg-warning text-dark') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        @endif
                    </div>
                    <p><strong>Total Amount:</strong> ₹ {{ number_format($order->total_amount, 2) }}</p>
                    <p><strong>Created At:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="card">
                <div class="card-header fw-bold">Customer Details</div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Ordered Products</div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th width="120">Price</th>
                                <th width="120">Quantity</th>
                                <th width="150">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>₹ {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₹ {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th>₹ {{ number_format($order->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
