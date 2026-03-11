@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Create New Product</a>
    </div>

    <div id="filterSection">
        <div class="card mb-4">
            <div class="card-body bg-light">
                <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ request('name') }}" placeholder="Search by name...">
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

                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
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
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Available Stock</th>
                            <th>Total Orders</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $product->name }}</td>
                                <td class="align-middle">₹ {{ number_format($product->price, 2) }}</td>
                                <td class="align-middle">{{ $product->stock }}</td>
                                <td class="align-middle">{{ $product->order_items_count }}</td>
                                <td class="align-middle">{{ $product->created_at->format('M d, Y H:i') }}</td>
                                <td class="align-middle">{{ $product->updated_at->format('M d, Y H:i') }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-sm btn-primary me-2">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
