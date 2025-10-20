<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UnitSeeder::class);

        // Create permissions
        $permissions = [
            'surat.create', 'surat.read', 'surat.update', 'surat.delete',
            'disposisi.create', 'disposisi.read', 'disposisi.update',
            'pengadaan.manage', 'direktur.approve', 'arsip.manage',
            'admin.access', 'user.manage', 'unit.manage', 'system.monitor'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $unitRole = Role::create(['name' => 'unit']);
        $pengadaanRole = Role::create(['name' => 'pengadaan']);
        $direkturRole = Role::create(['name' => 'direktur']);
        $adminRole = Role::create(['name' => 'admin']);

        // Assign permissions to roles
        $unitRole->givePermissionTo(['surat.create', 'surat.read', 'disposisi.read']);
        $pengadaanRole->givePermissionTo(['surat.read', 'surat.update', 'disposisi.create', 'pengadaan.manage']);
        $direkturRole->givePermissionTo(['surat.read', 'disposisi.create', 'direktur.approve', 'arsip.manage']);
        $adminRole->givePermissionTo(Permission::all());

        // Create users for each unit
        $unit1 = User::create([
            'name' => 'Kepala Unit 1',
            'email' => 'unit1@example.com',
            'password' => Hash::make('password'),
            'unit_id' => 1
        ]);
        $unit1->assignRole('unit');

        $unit2 = User::create([
            'name' => 'Kepala Unit 2',
            'email' => 'unit2@example.com',
            'password' => Hash::make('password'),
            'unit_id' => 2
        ]);
        $unit2->assignRole('unit');

        $pengadaan = User::create([
            'name' => 'Staff Pengadaan',
            'email' => 'pengadaan@example.com',
            'password' => Hash::make('password'),
            'unit_id' => 3
        ]);
        $pengadaan->assignRole('pengadaan');

        $direktur = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@example.com',
            'password' => Hash::make('password'),
            'unit_id' => 4
        ]);
        $direktur->assignRole('direktur');

        // Admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'unit_id' => null
        ]);
        $admin->assignRole('admin');
    }
}