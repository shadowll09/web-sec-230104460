<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modern Store - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #5c6bc0;
            --secondary-color: #ff5722;
            --dark-color: #212121;
            --light-color: #f5f5f5;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            transition: all 0.3s ease;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: white !important;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .card-header {
            border-bottom: none;
            padding: 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            border-radius: 5px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #3f51b5;
            border-color: #3f51b5;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(63, 81, 181, 0.3);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #43a047;
            border-color: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* Animation classes */
        .fade-out {
            animation: fadeOut 0.5s forwards;
        }

        .slide-up {
            animation: slideUp 0.3s forwards;
        }

        .scale-in {
            animation: scaleIn 0.3s forwards;
        }

        /* Animation keyframes */
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        @keyframes slideUp {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(-20px); opacity: 0; }
        }

        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Custom table styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
            transform: scale(1.01);
        }

        /* Badge styling */
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            border-radius: 30px;
        }

        /* Form control styling */
        .form-control {
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(92, 107, 192, 0.25);
        }

        /* Toast notification styling */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            animation: slideIn 0.3s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Container padding */
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    @include('layouts.menu')

    <!-- Add this menu item to your navigation -->
    @can('admin_users')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="bi bi-person-badge"></i> Role Management
        </a>
    </li>
    @endcan

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success animate__animated animate__fadeIn" role="alert" id="successAlert">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('successAlert').classList.remove('animate__fadeIn');
                    document.getElementById('successAlert').classList.add('animate__fadeOut');
                    setTimeout(function() {
                        document.getElementById('successAlert').style.display = 'none';
                    }, 500);
                }, 3000);
            </script>
        @endif

        @if(session('error'))
            <div class="alert alert-danger animate__animated animate__fadeIn" role="alert" id="errorAlert">
                {{ session('error') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('errorAlert').classList.remove('animate__fadeIn');
                    document.getElementById('errorAlert').classList.add('animate__fadeOut');
                    setTimeout(function() {
                        document.getElementById('errorAlert').style.display = 'none';
                    }, 500);
                }, 4000);
            </script>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning animate__animated animate__fadeIn" role="alert" id="warningAlert">
                {{ session('warning') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('warningAlert').classList.remove('animate__fadeIn');
                    document.getElementById('warningAlert').classList.add('animate__fadeOut');
                    setTimeout(function() {
                        document.getElementById('warningAlert').style.display = 'none';
                    }, 500);
                }, 4000);
            </script>
        @endif

        <div data-aos="fade-up" data-aos-duration="800">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            once: true,
            offset: 100,
        });

        // Function to handle item removal with animation
        function removeWithAnimation(element, animation, callback) {
            element.classList.add(animation);

            element.addEventListener('animationend', function() {
                if (callback) callback();
                element.remove();
            });
        }
    </script>

    <!-- Custom script for cart item animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to cart items
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach(item => {
                item.classList.add('animate__animated', 'animate__fadeIn');

                // Find remove buttons in cart
                const removeBtn = item.querySelector('.remove-item-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        // Animate item removal
                        item.classList.remove('animate__fadeIn');
                        item.classList.add('animate__fadeOutRight');

                        item.addEventListener('animationend', function() {
                            form.submit();
                        });
                    });
                }
            });

            // Add hover animations to product cards
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('scale-in');
                });

                card.addEventListener('mouseleave', function() {
                    this.classList.remove('scale-in');
                });
            });
        });
    </script>
</body>
</html>
