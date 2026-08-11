<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $medicine = Medicine::find($id);
            if ($medicine) {
                $subtotal = $medicine->price * $qty;
                $total += $subtotal;
                $items[] = ['medicine' => $medicine, 'quantity' => $qty, 'subtotal' => $subtotal];
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Medicine $medicine)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);
        $cart = Session::get('cart', []);
        $cart[$medicine->id] = ($cart[$medicine->id] ?? 0) + $request->quantity;
        Session::put('cart', $cart);

        return back()->with('success', "{$medicine->name} ditambahkan ke keranjang.");
    }

    public function remove(Medicine $medicine)
    {
        $cart = Session::get('cart', []);
        unset($cart[$medicine->id]);
        Session::put('cart', $cart);
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) return redirect()->route('medicines.index');

        $items = [];
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            $medicine = Medicine::find($id);
            if ($medicine) {
                $itemSubtotal = $medicine->price * $qty;
                $subtotal += $itemSubtotal;
                $items[] = ['medicine' => $medicine, 'quantity' => $qty, 'subtotal' => $itemSubtotal];
            }
        }

        $shippingCost = 15000;
        $total = $subtotal + $shippingCost;

        return view('cart.checkout', compact('items', 'subtotal', 'shippingCost', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) return redirect()->route('medicines.index');

        $subtotal = 0;
        $orderItems = [];

        foreach ($cart as $id => $qty) {
            $medicine = Medicine::findOrFail($id);
            $itemSubtotal = $medicine->price * $qty;
            $subtotal += $itemSubtotal;
            $orderItems[] = ['medicine' => $medicine, 'quantity' => $qty, 'price' => $medicine->price, 'subtotal' => $itemSubtotal];
        }

        $shippingCost = 15000;
        $total = $subtotal + $shippingCost;

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => Order::generateOrderNumber(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'medicine_id' => $item['medicine']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        Session::forget('cart');
        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat!');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.medicine')->orderByDesc('created_at')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) abort(403);
        $order->load('items.medicine');
        return view('orders.show', compact('order'));
    }

    public function payOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) abort(403);
        $order->update(['status' => 'confirmed']);
        return back()->with('success', 'Pembayaran pesanan berhasil dikonfirmasi!');
    }
}
