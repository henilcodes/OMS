<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'code'          => ['nullable', 'string'],
            'customer_name' => ['nullable', 'string'],
            'status'        => ['nullable', 'string'],
            'date_from'     => ['nullable', 'date'],
            'date_to'       => ['nullable', 'date'],
        ]);

        $code = $data['code'] ?? null;
        $customerName = $data['customer_name'] ?? null;
        $status       = $data['status'] ?? null;
        $dateFrom     = $data['date_from'] ?? null;
        $dateTo       = $data['date_to'] ?? null;

        $orders = Order::with(['customer', 'orderItems.product'])
            ->when($customerName, function ($query, $customerName) {
                $query->whereHas('customer', function ($q) use ($customerName) {
                    $q->where('name', 'like', "%{$customerName}%");
                });
            })
            ->when($code, fn($query, $code) => $query->where('code', 'like', "%{$code}%"))
            ->when($status, fn($query, $status) => $query->where('status', $status))
            ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            })
            ->when($dateFrom && !$dateTo, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when(!$dateFrom && $dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->withCount('orderItems')
            ->orderBy('status')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($validated['products'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for product: {$product->name}");
                }

                $product->stock -= $item['quantity'];
                $product->save();

                $price = $product->price * $item['quantity'];
                $totalAmount += $price;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            $order->orderItems()->createMany($orderItemsData);
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);
        $order->update(['status' => $validated['status']]);
        return redirect()->route('orders.index')->with('success', 'Order status updated successfully.');
    }
}
