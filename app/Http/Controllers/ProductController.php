<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'name'  => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
        ]);

        $name = $data['name'] ?? null;
        $dateFrom = $data['date_from'] ?? null;
        $dateTo = $data['date_to'] ?? null;

        $products = Product::when($name, function ($query, $name) {
            $query->where('name', 'like', "%{$name}%");
        })
            ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            })
            ->when($dateFrom && !$dateTo, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when(!$dateFrom && $dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->withCount('orderItems')
            ->orderBy('updated_at', 'desc')->paginate(10);

        return view('products.index', compact('products'));
    }


    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'numeric|min:0',
            'price' => 'numeric|min:0',
        ]);
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
}
