@extends('layouts.master')
@section('title', 'Welcome to Our Store')
@section('content')
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-md-12 text-center">
                <h1 class="display-4 fw-bold">Welcome to Our Online Store</h1>
                <p class="lead text-muted">Your one-stop shop for quality products</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                    <a href="{{ route('products_list') }}" class="btn btn-primary btn-lg px-4 gap-3">Browse Products</a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Sign Up</a>
                    @endguest
                </div>
            </div>
        </div>

        <!-- User Roles Section -->
        <div class="row mb-5">
            <div class="col-md-12 text-center mb-4">
                <h2 class="fw-bold">Our Platform Users</h2>
                <hr class="w-25 mx-auto">
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-circle fs-1 text-primary mb-3"></i>
                        <h3 class="card-title">Customers</h3>
                        <p class="card-text">Browse and purchase products with ease. Create an account to track your orders.</p>
                        @guest
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">Register Now</a>
                        @endguest
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-shop fs-1 text-success mb-3"></i>
                        <h3 class="card-title">Employees</h3>
                        <p class="card-text">Manage products, view customer lists, and help maintain our inventory.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-lock fs-1 text-danger mb-3"></i>
                        <h3 class="card-title">Administrators</h3>
                        <p class="card-text">Manage the entire system, including adding new employees and overseeing operations.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="row mb-5">
            <div class="col-md-12 text-center mb-4">
                <h2 class="fw-bold">Featured Products</h2>
                <hr class="w-25 mx-auto">
                <p>Check out some of our popular items</p>
                <a href="{{ route('products_list') }}" class="btn btn-primary">View All Products</a>
            </div>
        </div>
    </div>
@endsection
