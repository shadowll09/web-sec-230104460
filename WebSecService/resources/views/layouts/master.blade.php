<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Modern Store</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Base theme (Light) */
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --body-bg: #ffffff;
            --text-color: #212529;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --scrollbar-track: #f1f1f1;
            --scrollbar-thumb: #4a6cf7;
            
            /* Theme specific accent colors */
            --theme-primary: #4a6cf7;
            --theme-secondary: #6c757d;
            --theme-accent: #5c7cfa;
            --theme-gradient-start: #4a6cf7;
            --theme-gradient-end: #6384ff;
        }

        /* Dark mode variables */
        [data-theme="dark"] {
            --primary-color: var(--theme-primary);
            --secondary-color: #828a91;
            --success-color: #2fb84d;
            --danger-color: #e34757;
            --warning-color: #ffd119;
            --info-color: #1fb6ca;
            --light-color: #3c4349;
            --dark-color: #f8f9fa;
            --body-bg: #1a1d20;
            --text-color: #f8f9fa;
            --border-color: #495057;
            --card-bg: #2a2e33;
            --scrollbar-track: #2a2e33;
            --scrollbar-thumb: var(--theme-primary);
        }
        
        /* Energy Theme (Red) */
        [data-color-theme="energy"] {
            --theme-primary: #e63946;
            --theme-secondary: #ff6b6b;
            --theme-accent: #ff9999;
            --theme-gradient-start: #e63946;
            --theme-gradient-end: #ff6b6b;
        }
        
        /* Calm Theme (Green) */
        [data-color-theme="calm"] {
            --theme-primary: #2a9d8f;
            --theme-secondary: #57cc99;
            --theme-accent: #80ed99;
            --theme-gradient-start: #2a9d8f;
            --theme-gradient-end: #57cc99;
        }
        
        /* Ocean Theme (Blue) */
        [data-color-theme="ocean"] {
            --theme-primary: #0077b6;
            --theme-secondary: #00b4d8;
            --theme-accent: #90e0ef;
            --theme-gradient-start: #0077b6;
            --theme-gradient-end: #00b4d8;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        /* Cards */
        .card {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--theme-primary);
            border-color: var(--theme-primary);
        }

        .btn-primary:hover {
            background-color: var(--theme-primary);
            filter: brightness(110%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Gradient buttons for energy */
        .btn-gradient {
            background-image: linear-gradient(to right, var(--theme-gradient-start), var(--theme-gradient-end));
            border: none;
            color: white;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .btn-gradient:hover {
            color: white;
        }
        
        .btn-gradient:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(to right, var(--theme-gradient-end), var(--theme-gradient-start));
            opacity: 0;
            z-index: -1;
            transition: opacity 0.35s ease;
        }
        
        .btn-gradient:hover:before {
            opacity: 1;
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: var(--card-bg) !important;
            transition: background-color 0.3s;
        }

        .navbar-light .navbar-nav .nav-link {
            color: var(--text-color);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.2s;
        }

        .navbar-light .navbar-brand {
            color: var(--text-color);
            font-weight: 700;
            transition: color 0.3s;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Form controls */
        .form-control {
            border-radius: 6px;
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-color);
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s, color 0.3s;
        }

        .form-control:focus {
            border-color: var(--theme-primary);
            box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.25);
        }

        /* Tables */
        .table {
            color: var(--text-color);
            transition: color 0.3s;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Animation utility classes */
        .scale-in {
            transform: scale(1.03);
        }

        /* Dropdown menus */
        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            transition: background-color 0.3s, border-color 0.3s;
        }

        .dropdown-item {
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        /* Badge styling */
        .badge {
            transition: background-color 0.3s;
        }
        
        .badge-themed {
            background-color: var(--theme-primary);
            color: white;
        }

        /* Notification panel styles */
        .notification-panel {
            margin-bottom: 20px;
        }

        .notification-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-item {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .notification-content {
            flex: 1;
        }
        
        /* Theme selector */
        .theme-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .theme-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .theme-option:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .theme-option.active {
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--theme-primary);
        }
        
        .theme-option.energy {
            background: linear-gradient(135deg, #e63946 0%, #ff6b6b 100%);
        }
        
        .theme-option.calm {
            background: linear-gradient(135deg, #2a9d8f 0%, #57cc99 100%);
        }
        
        .theme-option.ocean {
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
        }
        
        .theme-option.default {
            background: linear-gradient(135deg, #4a6cf7 0%, #6384ff 100%);
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
            background-color: var(--scrollbar-track);
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--theme-primary);
            border-radius: 10px;
        }

        /* Dark mode toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, background-color 0.3s;
        }

        .dark-mode-toggle:hover {
            transform: translateY(-5px);
        }

        .dark-mode-toggle i {
            font-size: 1.5rem;
            color: var(--text-color);
        }
    </style>
</head>
<body>
    @include('layouts.menu')

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

        <!-- Notifications Area - Add this where appropriate in your layout -->
        @auth
            @if(Auth::user()->hasAnyRole(['Admin', 'Employee']) && isset($feedbackNotifications) && $unreadFeedbackCount > 0)
            <div class="notification-panel">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i> Notifications
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadFeedbackCount }}
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                        <li><h6 class="dropdown-header">Customer Feedback & Cancellations</h6></li>
                        
                        @foreach($feedbackNotifications as $notification)
                            <li>
                                <a class="dropdown-item" href="{{ $notification->data['url'] }}">
                                    @if($notification->type == 'App\Notifications\OrderCancelled')
                                        <div class="notification-item">
                                            <div class="notification-icon bg-danger text-white">
                                                <i class="bi bi-x-circle"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="mb-0"><strong>Order #{{ $notification->data['order_id'] }} cancelled</strong></p>
                                                <p class="small text-muted mb-0">{{ $notification->data['customer_name'] }} - {{ $notification->data['reason'] }}</p>
                                                <p class="small text-muted">{{ \Carbon\Carbon::parse($notification->data['cancelled_at'])->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @elseif($notification->type == 'App\Notifications\NewFeedback')
                                        <div class="notification-item">
                                            <div class="notification-icon bg-warning text-dark">
                                                <i class="bi bi-chat-left-text"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="mb-0"><strong>New feedback received</strong></p>
                                                <p class="small text-muted mb-0">{{ $notification->data['customer_name'] }} - Order #{{ $notification->data['order_id'] }}</p>
                                                <p class="small text-muted">{{ \Carbon\Carbon::parse($notification->data['submitted_at'])->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                        
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center">
                            <a class="dropdown-item" href="{{ route('feedback.index') }}">
                                View all feedback
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        @endauth

        <div data-aos="fade-up" data-aos-duration="800">
            @yield('content')
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <div class="dark-mode-toggle animate__animated animate__fadeIn" id="darkModeToggle">
        <i class="bi bi-sun"></i>
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

        // Dark mode functionality
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const icon = darkModeToggle.querySelector('i');
            
            // Check for saved theme preference or use preferred color scheme
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.setAttribute('data-theme', 'dark');
                icon.classList.replace('bi-sun', 'bi-moon-stars');
            }
            
            // Get color theme preference
            const savedColorTheme = localStorage.getItem('colorTheme');
            if (savedColorTheme) {
                document.documentElement.setAttribute('data-color-theme', savedColorTheme);
            }
            
            // Toggle theme on click
            darkModeToggle.addEventListener('click', function() {
                if (document.documentElement.getAttribute('data-theme') === 'dark') {
                    document.documentElement.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    icon.classList.replace('bi-moon-stars', 'bi-sun');
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    icon.classList.replace('bi-sun', 'bi-moon-stars');
                }
            });
            
            // Set up color theme switchers if they exist
            const themeOptions = document.querySelectorAll('.theme-option');
            themeOptions.forEach(option => {
                // Check if this option is the active one
                const themeName = option.getAttribute('data-theme');
                if (themeName === savedColorTheme) {
                    option.classList.add('active');
                }
                
                option.addEventListener('click', function() {
                    const themeName = this.getAttribute('data-theme');
                    
                    // Remove active class from all options
                    themeOptions.forEach(opt => opt.classList.remove('active'));
                    
                    // Add active class to clicked option
                    this.classList.add('active');
                    
                    if (themeName === 'default') {
                        // Remove color theme attribute
                        document.documentElement.removeAttribute('data-color-theme');
                        localStorage.removeItem('colorTheme');
                    } else {
                        // Set new color theme
                        document.documentElement.setAttribute('data-color-theme', themeName);
                        localStorage.setItem('colorTheme', themeName);
                    }
                });
            });
        });
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
