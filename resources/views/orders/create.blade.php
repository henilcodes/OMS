@extends('layouts.app')
@section('title','Create Order')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create New Order</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                @csrf
                <div class="mb-4">
                    <label for="customer_id" class="form-label">Select Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select" required>
                        <option value="">-- Select Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <h4 class="mb-3">Select Products</h4>
                @php
                    $oldProducts = old('products', [['id' => '', 'quantity' => 1]]);
                @endphp
                <div id="product-container">

                    @foreach ($oldProducts as $index => $oldProduct)
                        <div class="product-row row align-items-end mb-3">

                            <div class="col-md-8">
                                <label class="form-label">Product</label>
                                <select name="products[{{ $index }}][id]" class="form-select product-select" required
                                    onchange="calculateTotal()">

                                    <option value="">-- Select Product --</option>

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}"
                                            {{ ($oldProduct['id'] ?? '') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} - ₹ {{ $product->price }} (Stock: {{ $product->stock }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="products[{{ $index }}][quantity]"
                                    class="form-control product-qty" min="1" required
                                    value="{{ $oldProduct['quantity'] }}" onchange="calculateTotal()">
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger w-100" onclick="removeRow(this)">
                                    Remove
                                </button>
                            </div>

                        </div>
                    @endforeach

                </div>

                <button type="button" class="btn btn-success mb-4" onclick="addProductRow()">
                    + Add Another Product
                </button>

                <div class="mb-4 fs-4 fw-bold">
                    Total Amount: ₹ <span id="total_amount_display">0.00</span>
                </div>

                <button type="submit" class="btn btn-primary">Create Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>

            </form>
        </div>
    </div>


    {{-- Row Template --}}
    <template id="product-row-template">
        <div class="product-row row align-items-end mb-3">

            <div class="col-md-8">
                <label class="form-label">Product</label>
                <select class="form-select product-select" required onchange="calculateTotal()">

                    <option value="">-- Select Product --</option>

                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}">
                            {{ $product->name }} - ₹ {{ $product->price }} (Stock: {{ $product->stock }})
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control product-qty" min="1" value="1" required
                    onchange="calculateTotal()">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100" onclick="removeRow(this)">
                    Remove
                </button>
            </div>

        </div>
    </template>


    <script>
        let rowCount = {{ count($oldProducts) }};

        function addProductRow() {
            const container = document.getElementById('product-container');
            const template = document.getElementById('product-row-template');

            const clone = template.content.cloneNode(true);

            const select = clone.querySelector('.product-select');
            const qty = clone.querySelector('.product-qty');

            select.name = `products[${rowCount}][id]`;
            qty.name = `products[${rowCount}][quantity]`;

            container.appendChild(clone);

            rowCount++;

            calculateTotal();
        }


        function removeRow(btn) {
            const rows = document.querySelectorAll('.product-row');

            if (rows.length > 1) {
                btn.closest('.product-row').remove();
                calculateTotal();
            } else {
                alert("You must have at least one product in the order.");
            }
        }


        function calculateTotal() {
            let total = 0;

            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {

                const select = row.querySelector('.product-select');
                const qtyInput = row.querySelector('.product-qty');

                if (select && select.selectedIndex > 0 && qtyInput.value) {
                    const price = parseFloat(
                        select.options[select.selectedIndex].getAttribute('data-price')
                    );

                    const qty = parseInt(qtyInput.value);

                    total += price * qty;
                }

            });

            document.getElementById('total_amount_display').textContent = total.toFixed(2);
        }


        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });
    </script>
@endsection
