<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['index']);
    }

    /**
     * Display a listing of the customer's orders.
     */
    public function index()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If employee or admin, show all orders
        if ($user->hasAnyRole(['Admin', 'Employee'])) {
            $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
            return view('orders.index', compact('orders'));
        }

        // For customers, only show their orders
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Check if user has permission to view this order
        $user = Auth::user();
        if (!$user->hasAnyRole(['Admin', 'Employee']) && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('items.product', 'user');
        return view('orders.show', compact('order'));
    }

    /**
     * Add a product to the cart
     */
    public function addToCart(Request $request, Product $product)
    {
        // Check if product is in stock (even if stock_quantity is 1, it's still available)
        if ($product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock.');
        }

        // Get requested quantity and ensure it's an integer
        $requestedQuantity = (int) $request->input('quantity', 1);

        // Prevent negative quantities
        if ($requestedQuantity <= 0) {
            return redirect()->back()->with('error', 'Quantity must be positive.');
        }

        // Initialize cart if it doesn't exist
        $cart = Session::get('cart', []);

        // Get original quantity in cart
        $originalQuantity = isset($cart[$product->id]) ? (int) $cart[$product->id]['quantity'] : 0;

        // Make sure we don't exceed available stock
        if ($requestedQuantity > $product->stock_quantity) {
            // We can still add the last item
            $requestedQuantity = $product->stock_quantity;
            session()->flash('warning', "Only {$product->stock_quantity} item(s) available. We've adjusted your quantity.");
        }

        // Add or update product in cart
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $originalQuantity + $requestedQuantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $requestedQuantity
            ];
        }

        // Update product stock by reducing the requested quantity
        if (!$product->updateStock($requestedQuantity)) {
            // This should no longer happen since we've validated quantities above,
            // but keep as a safeguard
            \Log::error("Failed to update stock for product {$product->id} with quantity {$requestedQuantity}");
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] = $originalQuantity;
            }
            Session::put('cart', $cart);
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Log the successful stock update
        \Log::info("Successfully updated stock for product {$product->id}. New stock level: {$product->stock_quantity}");

        Session::put('cart', $cart);

        return redirect()->back()->with('success', "Added {$requestedQuantity} {$product->name}(s) to cart successfully!");
    }

    /**
     * Display the cart
     */
    public function cart()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $user = Auth::user();

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('orders.cart', compact('cart', 'total', 'user'));
    }

    /**
     * Remove an item from the cart
     */
    public function removeFromCart(Request $request, $productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            // Get the quantity being removed
            $quantityToRestore = (int) $cart[$productId]['quantity'];

            // Get the product and restore stock quantity
            $product = Product::find($productId);
            if ($product && $quantityToRestore > 0) {
                // Use negative quantity to increase stock
                $product->updateStock(-$quantityToRestore);

                // Log what we're doing
                \Log::info("Restoring {$quantityToRestore} items to stock for product {$productId}");
            }

            // Remove from cart
            unset($cart[$productId]);
            Session::put('cart', $cart);

            return redirect()->back()->with('success', 'Product removed from cart and stock restored.');
        }

        return redirect()->back();
    }

    /**
     * Proceed to checkout
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products_list')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        $user = Auth::user();

        // Validate stock and recalculate total
        $outOfStockItems = [];
        $stockWarnings = [];

        // First calculate total based on current cart
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Then check stock availability but don't remove items that were already in cart
        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            // If product doesn't exist anymore, remove it
            if (!$product) {
                unset($cart[$id]);
                Session::put('cart', $cart);
                session()->flash('error', "A product in your cart is no longer available and has been removed.");
                continue;
            }

            // If the quantity in cart is more than available in stock (but not 0),
            // just display a warning but don't remove from cart
            if ($product->stock_quantity < $item['quantity'] && $product->stock_quantity > 0) {
                $stockWarnings[] = "Only {$product->stock_quantity} of {$item['name']} available (you have {$item['quantity']} in cart).";
            }

            // Only if product is completely out of stock AND not already in cart,
            // mark it as out of stock
            if ($product->stock_quantity == 0) {
                $outOfStockItems[] = $product->name;
            }
        }

        // Display warnings about stock quantities
        if (!empty($stockWarnings)) {
            foreach ($stockWarnings as $warning) {
                session()->flash('warning', $warning);
            }
        }

        // Display out of stock messages but don't remove items
        if (!empty($outOfStockItems)) {
            $message = count($outOfStockItems) > 1
                ? "These items have limited availability: " . implode(', ', $outOfStockItems)
                : "This item has limited availability: " . $outOfStockItems[0];
            session()->flash('warning', $message);
        }

        if (empty($cart)) {
            return redirect()->route('products_list')->with('error', 'There are no items in your cart.');
        }

        // Check if user has enough credits
        if ($user->credits < $total) {
            return view('orders.insufficient_credits', compact('total', 'user'));
        }

        return view('orders.checkout', compact('cart', 'total', 'user'));
    }

    /**
     * Place an order
     */
    public function placeOrder(Request $request)
    {
        // Validate request
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products_list')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();

        // Use a database transaction to ensure atomicity
        return DB::transaction(function() use ($cart, $user, $request) {
            $total = 0;
            $orderItems = [];
            $stockIssues = false;

            // Calculate total and verify stock is still available
            foreach ($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);

                if (!$product) {
                    DB::rollBack();
                    return redirect()->route('cart')->with('error', 'A product has been removed from our system.');
                }

                // Items that were already in cart can still be purchased even if stock is now at 0
                // because we already adjusted the stock when adding to cart
                $total += $item['price'] * $item['quantity'];
                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }

            // Check if user has enough credits
            if ($user->credits < $total) {
                DB::rollBack();
                return view('orders.insufficient_credits', ['total' => $total, 'user' => $user]);
            }

            // Create order
            $order = new Order();
            $order->user_id = $user->id;
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->shipping_address = $request->shipping_address;
            $order->billing_address = $request->billing_address;
            $order->save();

            // Create order items (stock was already updated when adding to cart)
            foreach ($orderItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product']->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->save();
            }

            // Deduct credits from user account
            $user->deductCredits($total);

            // Clear cart
            Session::forget('cart');

            // Return to confirmation page
            return redirect()->route('orders.confirmation', ['order' => $order->id])
                ->with('success', 'Your order has been placed successfully!');
        });
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);

        // Security check: only the order owner can see the confirmation
        if (Auth::id() != $order->user_id && !Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.confirmation', compact('order'));
    }

    /**
     * Update order status (Admin/Employee only)
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Check permissions
        if (!Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated.');
    }

    /**
     * List all customers (for employees)
     */
    public function listCustomers()
    {
        // Check if user has permission
        if (!Auth::user()->hasPermissionTo('list_customers')) {
            abort(403, 'Unauthorized action.');
        }

        $customers = User::role('Customer')->get();
        return view('users.customers', compact('customers'));
    }

    /**
     * Add credits to customer (for employees and admins)
     */
    public function addCreditsForm(User $user)
    {
        // Check permissions
        if (!Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure target user is a customer
        if (!$user->hasRole('Customer')) {
            return redirect()->back()->with('error', 'Credits can only be added to customer accounts.');
        }

        return view('users.add_credits', compact('user'));
    }

    /**
     * Process adding credits to a customer
     */
    public function addCredits(Request $request, User $user)
    {
        // Check permissions
        if (!Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }

        // Validate request
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        // Ensure target user is a customer
        if (!$user->hasRole('Customer')) {
            return redirect()->back()->with('error', 'Credits can only be added to customer accounts.');
        }

        // Add credits
        $user->addCredits($request->amount);

        return redirect()->route('list_customers')
            ->with('success', "Successfully added {$request->amount} credits to {$user->name}'s account.");
    }
}
