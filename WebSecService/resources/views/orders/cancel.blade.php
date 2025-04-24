@extends('layouts.master')
@section('title', 'Cancel Order')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0"><i class="bi bi-x-circle me-2"></i>Cancel Order #{{ $order->id }}</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Please note:</strong> Cancelling this order will refund {{ number_format($order->total_amount, 2) }} credits to your account.
                    </div>
                    
                    <div class="mb-4">
                        <h5>Order Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total Amount:</td>
                                        <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" id="cancelForm">
                        @csrf
                        <div class="mb-4">
                            <h5>Please tell us why you're cancelling this order:</h5>
                            
                            <div class="mb-3">
                                @foreach($reasons as $value => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="reason" id="reason_{{ $value }}" value="{{ $label }}" {{ old('reason') == $label ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="reason_{{ $value }}">
                                        {{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="mb-3">
                                <label for="comments" class="form-label">Additional Comments:</label>
                                <textarea class="form-control" id="comments" name="comments" rows="3" placeholder="Please provide any additional details about your cancellation...">{{ old('comments') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Order
                            </a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                <i class="bi bi-x-circle me-1"></i> Confirm Cancellation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
