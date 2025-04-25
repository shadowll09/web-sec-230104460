@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">User Profile</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="30%">Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                @if($user->hasRole('Customer'))
                                <tr>
                                    <th>Credits</th>
                                    <td>
                                        <span class="badge badge-themed fs-6">${{ number_format($user->credits, 2) }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Roles</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Permissions</th>
                                    <td>
                                        @foreach($permissions as $permission)
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if($user->hasRole('Customer'))
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h4 class="mb-0">Account Balance</h4>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-4">${{ number_format($user->credits, 2) }}</h2>
                                    <p class="lead">Available Credits</p>

                                    @if(auth()->user()->hasAnyRole(['Admin', 'Employee']) && auth()->id() != $user->id)
                                        <a href="{{ route('add_credits_form', $user) }}" class="btn btn-success">
                                            <i class="bi bi-plus-circle"></i> Add Credits
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                            <a class="btn btn-primary me-2" href="{{ route('edit_password', $user->id) }}">Change Password</a>
                        @endif

                        @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-success">Edit Profile</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Theme Customization Panel -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-palette me-2"></i>Customize Theme</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Color Themes</h5>
                    <p class="text-muted mb-3">Select a color theme that matches your energy level and mood.</p>
                    
                    <div class="theme-selector mb-4">
                        <div class="theme-option default" data-theme="default" title="Default (Blue)"></div>
                        <div class="theme-option energy" data-theme="energy" title="Energy (Red)"></div>
                        <div class="theme-option calm" data-theme="calm" title="Calm (Green)"></div>
                        <div class="theme-option ocean" data-theme="ocean" title="Ocean (Blue)"></div>
                    </div>
                    
                    <h5 class="mb-3">Dark Mode</h5>
                    <p class="text-muted mb-3">Toggle between light and dark mode.</p>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                        <label class="form-check-label" for="darkModeSwitch">Enable Dark Mode</label>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" class="btn btn-gradient w-100">
                            <i class="bi bi-check-circle me-2"></i>Apply Theme
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Preview</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">Primary Button</button>
                        <button class="btn btn-gradient">Gradient Button</button>
                        <div class="alert alert-themed p-2 mb-0">
                            <small>Your selected theme will be applied across the site.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up dark mode toggle switch
        const darkModeSwitch = document.getElementById('darkModeSwitch');
        
        // Check current theme setting
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        darkModeSwitch.checked = isDarkMode;
        
        // Set up dark mode toggle
        darkModeSwitch.addEventListener('change', function() {
            if (this.checked) {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                
                // Update the main toggle icon if it exists
                const darkModeToggle = document.getElementById('darkModeToggle');
                if (darkModeToggle) {
                    const icon = darkModeToggle.querySelector('i');
                    if (icon) icon.classList.replace('bi-sun', 'bi-moon-stars');
                }
            } else {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                
                // Update the main toggle icon if it exists
                const darkModeToggle = document.getElementById('darkModeToggle');
                if (darkModeToggle) {
                    const icon = darkModeToggle.querySelector('i');
                    if (icon) icon.classList.replace('bi-moon-stars', 'bi-sun');
                }
            }
        });
        
        // Set up theme buttons
        const themeButtons = document.querySelectorAll('.theme-option');
        const savedColorTheme = localStorage.getItem('colorTheme') || 'default';
        
        // Set active class for current theme
        themeButtons.forEach(btn => {
            const themeName = btn.getAttribute('data-theme');
            if (themeName === savedColorTheme) {
                btn.classList.add('active');
            }
            
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                themeButtons.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
            });
        });
        
        // Handle the Apply Theme button
        document.querySelector('.btn-gradient').addEventListener('click', function() {
            const activeTheme = document.querySelector('.theme-option.active');
            if (activeTheme) {
                const themeName = activeTheme.getAttribute('data-theme');
                
                if (themeName === 'default') {
                    document.documentElement.removeAttribute('data-color-theme');
                    localStorage.removeItem('colorTheme');
                } else {
                    document.documentElement.setAttribute('data-color-theme', themeName);
                    localStorage.setItem('colorTheme', themeName);
                }
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success animate__animated animate__fadeIn mt-3';
                alert.textContent = 'Theme applied successfully!';
                this.parentNode.appendChild(alert);
                
                // Remove alert after 3 seconds
                setTimeout(() => {
                    alert.classList.remove('animate__fadeIn');
                    alert.classList.add('animate__fadeOut');
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            }
        });
    });
</script>
@endpush
@endsection
