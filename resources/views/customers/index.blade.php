@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customers</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Create New Customer</a>
    </div>

    <div id="filterSection">
        <div class="card mb-4">
            <div class="card-body bg-light">
                <form method="GET" action="{{ route('customers.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ request('name') }}" placeholder="Search by name...">
                    </div>
                    <div class="col-md-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ request('email') }}" placeholder="Search by email...">
                    </div>
                    <div class="col-md-2">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ request('phone') }}" placeholder="Search by phone...">
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
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Reset</a>
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
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Orders</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $customer->name }}</td>
                                <td class="align-middle">{{ $customer->email }}</td>
                                <td class="align-middle">{{ $customer->phone }}</td>
                                <td class="align-middle">{{ $customer->orders_count }}</td>
                                <td class="align-middle">{{ $customer->created_at->format('M d, Y H:i') }}</td>
                                <td class="align-middle">{{ $customer->updated_at->format('M d, Y H:i') }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('customers.edit', $customer) }}"
                                        class="btn btn-primary btn-sm me-2">Edit</a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this customer?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>

                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
@endsection
