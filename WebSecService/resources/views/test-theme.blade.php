@extends('layouts.master')
@section('title', 'Theme Test')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Theme Test Page</h2>
        </div>
        <div class="card-body">
            <p class="lead">This page tests all theme features to ensure they're working correctly.</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">Theme Selection</h4>
                        </div>
                        <div class="card-body">
                            <h5 class="text-primary mb-3">Color Themes</h5>
                            
                            <div class="theme-selector mb-4">
                                <div class="theme-option default" data-theme="default" title="Default (Blue)"></div>
                                <div class="theme-option energy" data-theme="energy" title="Energy (Red)"></div>
                                <div class="theme-option calm" data-theme="calm" title="Calm (Green)"></div>
                                <div class="theme-option ocean" data-theme="ocean" title="Ocean (Blue)"></div>
                            </div>
                            
                            <h5 class="text-primary mb-3">Dark Mode</h5>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                                <label class="form-check-label" for="darkModeSwitch">Enable Dark Mode</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">Theme Preview</h4>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-3">UI Elements</h5>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-primary">Primary Button</button>
                                <button class="btn btn-secondary">Secondary Button</button>
                                <button class="btn btn-gradient">Gradient Button</button>
                            </div>
                            
                            <div class="alert alert-primary mb-3">
                                This is a primary alert
                            </div>
                            
                            <div class="alert alert-themed mb-3">
                                This is a themed alert
                            </div>
                            
                            <div class="mb-3">
                                <label for="exampleInput" class="form-label">Example Input</label>
                                <input type="text" class="form-control" id="exampleInput" placeholder="Type something...">
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">Primary</span>
                                <span class="badge bg-secondary">Secondary</span>
                                <span class="badge bg-success">Success</span>
                                <span class="badge bg-danger">Danger</span>
                                <span class="badge bg-warning text-dark">Warning</span>
                                <span class="badge bg-info text-dark">Info</span>
                                <span class="badge badge-themed">Themed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Table Example</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>John Doe</td>
                                <td>john@example.com</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jane Smith</td>
                                <td>jane@example.com</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Robert Johnson</td>
                                <td>robert@example.com</td>
                                <td><span class="badge bg-danger">Inactive</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Current Theme Status</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Color Theme:</strong> <span id="currentColorTheme">Default</span>
                    </div>
                    <div>
                        <strong>Dark Mode:</strong> <span id="darkModeStatus">Off</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update current theme status display
        function updateThemeStatus() {
            const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
            const colorTheme = document.documentElement.getAttribute('data-color-theme') || 'default';
            
            // Update status indicators
            document.getElementById('darkModeStatus').textContent = isDarkMode ? 'On' : 'Off';
            
            let colorThemeName = 'Default';
            if (colorTheme === 'energy') colorThemeName = 'Energy (Red)';
            if (colorTheme === 'calm') colorThemeName = 'Calm (Green)';
            if (colorTheme === 'ocean') colorThemeName = 'Ocean (Blue)';
            
            document.getElementById('currentColorTheme').textContent = colorThemeName;
        }
        
        // Initial update
        updateThemeStatus();
        
        // Set up observer to monitor theme changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && 
                   (mutation.attributeName === 'data-theme' || 
                    mutation.attributeName === 'data-color-theme')) {
                    updateThemeStatus();
                }
            });
        });
        
        // Start observing
        observer.observe(document.documentElement, { 
            attributes: true, 
            attributeFilter: ['data-theme', 'data-color-theme']
        });
    });
</script>
@endpush
@endsection 