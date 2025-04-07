@extends('layouts.master')
@section('title', 'Order Details')
@section('content')
<div class="container py-4">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Order #{{ $order->id }}</h3>
                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'delivered' ? 'success' : 'info') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Order Date</h5>
                            <p>{{ $order->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer</h5>
                            <p>{{ $order->user->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Shipping Address</h5>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Billing Address</h5>
                            <p>{{ $order->billing_address }}</p>
                        </div>
                    </div>

                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Order Actions</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->hasAnyRole(['Admin', 'Employee']))
                    <form action="{{ route('orders.update.status', $order->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
                        <a href="{{ route('products_list') }}" class="btn btn-success">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
