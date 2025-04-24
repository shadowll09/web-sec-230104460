<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ConfigFallbacks
{
    /**
     * Set up fallback configurations when critical services are unavailable
     *
     * @return void
     */
    public static function setup()
    {
        try {
            // Check if database is available by making a simple query
            \DB::connection()->getPdo();
            
            // Database is available, no need for fallbacks
            return;
        } catch (\Exception $e) {
            // Log the database connection error
            Log::error('Database connection failed. Setting up fallbacks: ' . $e->getMessage());
            
            // Set session driver to file instead of database
            Config::set('session.driver', 'file');
            
            // Set cache driver to file instead of database
            Config::set('cache.default', 'file');
            
            // Disable database-dependent features
            Config::set('database.connections.mysql.enabled', false);
        }
    }
}
