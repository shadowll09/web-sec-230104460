<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SecurityAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit {--fix : Attempt to fix some issues automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform a security audit on the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting WebSecService Security Audit...');
        $issues = 0;
        
        // Check security headers
        $this->info('Checking security headers...');
        if (!class_exists('\App\Http\Middleware\SecurityHeaders')) {
            $this->error('❌ SecurityHeaders middleware not found');
            $issues++;
        } else {
            $this->info('✅ SecurityHeaders middleware exists');
        }
        
        // Check .env file permissions
        $this->info('Checking .env file permissions...');
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $perms = fileperms($envPath);
            if (($perms & 0x0092) !== 0) {
                $this->error('❌ .env file has insecure permissions: ' . decoct($perms & 0777));
                $issues++;
                if ($this->option('fix')) {
                    chmod($envPath, 0600);
                    $this->info('🔧 Fixed .env file permissions');
                }
            } else {
                $this->info('✅ .env file has secure permissions');
            }
        }
        
        // Check storage directory permissions
        $this->info('Checking storage directory permissions...');
        $storagePath = storage_path();
        if (File::exists($storagePath)) {
            $perms = fileperms($storagePath);
            if (($perms & 0x0002) !== 0) {
                $this->error('❌ storage directory has world-writable permissions');
                $issues++;
                if ($this->option('fix')) {
                    chmod($storagePath, 0755);
                    $this->info('🔧 Fixed storage directory permissions');
                }
            } else {
                $this->info('✅ storage directory has secure permissions');
            }
        }
        
        // Check debug mode
        $this->info('Checking debug mode...');
        if (config('app.debug') === true && config('app.env') === 'production') {
            $this->error('❌ Debug mode enabled in production');
            $issues++;
            if ($this->option('fix')) {
                $this->warn('Cannot automatically disable debug mode. Please update your .env file manually.');
            }
        } else if (config('app.env') === 'production') {
            $this->info('✅ Debug mode correctly disabled in production');
        }
        
        // Check for HTTPS configuration
        $this->info('Checking HTTPS configuration...');
        if (config('app.env') === 'production' && !config('session.secure')) {
            $this->error('❌ Session cookies not set to secure in production');
            $issues++;
        } else if (config('app.env') === 'production') {
            $this->info('✅ Session cookies properly secured');
        }
        
        // Check for installed packages with vulnerabilities
        $this->info('Checking for package vulnerabilities...');
        $this->warn('This requires an external service. Consider running: composer audit');
        
        // Summary
        $this->newLine();
        if ($issues === 0) {
            $this->info('✅ Security audit completed. No issues found!');
        } else {
            $this->error("❌ Security audit completed. Found {$issues} potential issues.");
            if (!$this->option('fix')) {
                $this->info('Run with --fix option to attempt automatic fixes for some issues.');
            }
        }
        
        return $issues === 0 ? 0 : 1;
    }
}
