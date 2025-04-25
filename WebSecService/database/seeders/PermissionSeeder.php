<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Admin permissions
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_logs',
            'access_admin_panel',

            // Employee permissions
            'view_products',
            'manage_orders',
            'view_customers',
            'manage_feedback',

            // Feedback permissions
            'view_customer_feedback',
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
            'manage_notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('Admin');
        $employeeRole = Role::findByName('Employee');
        
        // Add existing permissions to Admin role
        $adminRole->givePermissionTo([
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_logs',
            'access_admin_panel',

            'view_customer_feedback',
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
            'manage_notifications',
        ]);
        
        // Add existing permissions to Employee role
        $employeeRole->givePermissionTo([
            'view_products',
            'manage_orders',
            'view_customers',
            'manage_feedback',

            'view_customer_feedback',
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
        ]);
    }
}