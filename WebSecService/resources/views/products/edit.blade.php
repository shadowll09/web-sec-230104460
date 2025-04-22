@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')

<div class="card m-4">
    <div class="card-header">
        <h1>{{ $product->id ? 'Edit' : 'Add' }} Product</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('products_save', $product->id ?? null) }}" method="post">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="code" class="form-label">Code:</label>
                    <input type="text" class="form-control" placeholder="Product Code" name="code" required value="{{ old('code', $product->code) }}">
                </div>
                <div class="col-md-6">
                    <label for="model" class="form-label">Model:</label>
                    <input type="text" class="form-control" placeholder="Product Model" name="model" required value="{{ old('model', $product->model) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" placeholder="Product Name" name="name" required value="{{ old('name', $product->name) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="price" class="form-label">Price:</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" class="form-control" placeholder="0.00" name="price" required value="{{ old('price', $product->price) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="stock_quantity" class="form-label">Stock Quantity:</label>
                    <input type="number" min="0" class="form-control" placeholder="0" name="stock_quantity" required value="{{ old('stock_quantity', $product->stock_quantity) }}">
                </div>
                <div class="col-md-4">
                    <label for="photo" class="form-label">Photo:</label>
                    <input type="text" class="form-control" placeholder="image.jpg" name="photo" value="{{ old('photo', $product->photo) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" placeholder="Product description" name="description" required rows="3">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products_list') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

@endsection
