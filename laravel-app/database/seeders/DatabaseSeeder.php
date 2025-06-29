<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Modules\PosCore\Models\Table;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Tenant
        $tenant = Tenant::firstOrCreate(['name' => 'Awesome Restaurant']);

        // Create Permissions
        $permissions = [
            'customer.read', 'customer.create', 'customer.update', 'customer.delete',
            'order.read', 'order.create', 'order.update', 'order.delete',
            'notification.send',
            'report.view',
            'table.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        $adminRole->givePermissionTo(Permission::all());
        $managerRole->givePermissionTo(['customer.read', 'customer.create', 'order.read', 'order.create', 'notification.send', 'report.view', 'table.manage']);
        $staffRole->givePermissionTo(['order.read', 'order.create', 'table.manage']);

        // Create Users and assign Roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]
        );
        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]
        );
        $manager->assignRole('manager');

        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]
        );
        $staff->assignRole('staff');

        // Create Tables for the tenant
        Table::firstOrCreate(['name' => 'Table 1', 'tenant_id' => $tenant->id]);
        Table::firstOrCreate(['name' => 'Table 2', 'tenant_id' => $tenant->id]);

        $this->command->info('Database seeded with tenants, users, roles, and permissions.');
    }
}
