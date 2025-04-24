<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;

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
            // Check if facades are initialized before using them
            if (!app()->resolved('db')) {
                self::setupFallbacks('Facades not initialized yet');
                return;
            }

            // Check if database is available by making a simple query
            try {
                DB::connection()->getPdo();
                // Database is available, no need for fallbacks
                return;
            } catch (Exception $dbException) {
                // Database connection failed, set up fallbacks
                self::setupFallbacks($dbException->getMessage());
            }
        } catch (Exception $e) {
            // If an unexpected error occurs, still try to set up fallbacks
            self::setupEmergencyFallbacks();
        }
    }

    /**
     * Set up fallback configurations
     * 
     * @param string $reason The reason for setting up fallbacks
     * @return void
     */
    private static function setupFallbacks($reason)
    {
        // Log the database connection error if Log facade is available
        if (app()->resolved('log')) {
            Log::error('Database connection failed. Setting up fallbacks: ' . $reason);
        }
        
        // Set session driver to file instead of database
        Config::set('session.driver', 'file');
        
        // Set cache driver to file instead of database
        Config::set('cache.default', 'file');
        Config::set('cache.stores.file.driver', 'file');
        
        // Ensure cache path exists and is writable
        $cachePath = storage_path('framework/cache/data');
        if (!is_dir($cachePath)) {
            @mkdir($cachePath, 0755, true);
        }
        
        // Set queue driver to sync instead of database
        Config::set('queue.default', 'sync');
        
        // Disable features that might depend on database
        Config::set('auth.guards.web.driver', 'array');
        
        // Disable database-dependent features
        Config::set('database.connections.mysql.enabled', false);
        
        // Set maintenance mode to use file driver instead of database
        Config::set('app.maintenance.driver', 'file');
        
        // For commands specifically, we need to make sure they can run without DB
        if (app()->runningInConsole()) {
            Config::set('app.debug', true);
        }
    }

    /**
     * Emergency fallbacks when even Config facade is unavailable
     * 
     * @return void
     */
    private static function setupEmergencyFallbacks()
    {
        // If we can't even use Config facade, try to use the app container directly
        $app = app();
        if ($app && method_exists($app, 'make')) {
            try {
                $config = $app->make('config');
                if ($config) {
                    $config->set('session.driver', 'file');
                    $config->set('cache.default', 'file');
                    $config->set('queue.default', 'sync');
                }
            } catch (Exception $e) {
                // Last resort: nothing we can do here
            }
        }
    }
}
