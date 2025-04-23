<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register common password validation rules
        $this->app->singleton('password.rules', function ($app) {
            return [
                'required',
                'confirmed',
                Password::min(12)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised(3)
            ];
        });
        
        // Add custom validation rules for security
        Validator::extend('no_script_tags', function ($attribute, $value, $parameters, $validator) {
            return !preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $value);
        }, 'The :attribute may not contain script tags.');
        
        Validator::extend('safe_html', function ($attribute, $value, $parameters, $validator) {
            // Strip potentially dangerous tags
            $sanitized = strip_tags($value, '<p><br><strong><em><ul><ol><li><a><span><div><h1><h2><h3><h4><h5><h6><blockquote>');
            return $value === $sanitized;
        }, 'The :attribute contains potentially dangerous HTML.');
    }
}
