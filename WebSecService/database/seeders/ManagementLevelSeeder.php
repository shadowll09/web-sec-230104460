<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ManagementLevelSeeder extends Seeder
{
    /**
     * Seed management levels for users based on roles.
     */
    public function run(): void
    {
        $this->command->info('Assigning management levels to users...');
        
        // Assign high level management to Admins
        $admins = User::role('Admin')->get();
        foreach ($admins as $admin) {
            $admin->management_level = User::MANAGEMENT_LEVEL_HIGH;
            $admin->save();
            $this->command->info("Assigned HIGH management level to {$admin->name} (Admin)");
        }
        
        // Assign middle level management to Employees
        $employees = User::role('Employee')->get();
        foreach ($employees as $employee) {
            // Skip if they're already an admin with high level
            if ($employee->management_level === User::MANAGEMENT_LEVEL_HIGH) {
                continue;
            }
            
            $employee->management_level = User::MANAGEMENT_LEVEL_MIDDLE;
            $employee->save();
            $this->command->info("Assigned MIDDLE management level to {$employee->name} (Employee)");
        }
        
        // Assign low level management to select customers
        // For demonstration, we'll assign low management to customers with "supervisor" in their name
        $customers = User::role('Customer')
            ->where('name', 'like', '%supervisor%')
            ->get();
            
        foreach ($customers as $customer) {
            $customer->management_level = User::MANAGEMENT_LEVEL_LOW;
            $customer->save();
            $this->command->info("Assigned LOW management level to {$customer->name} (Customer with Supervisor privileges)");
        }
        
        $this->command->info('Management levels assigned successfully!');
    }
}
