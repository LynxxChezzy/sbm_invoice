<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar izin
        $permissions = [
            'tambah-user',
            'edit-user',
            'lihat-user',
            'hapus-user',
            'tambah-data',
            'edit-data',
            'lihat-data',
            'hapus-data',
        ];

        // Membuat izin
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Membuat role
        $roleAdmin = Role::create(['name' => 'Administrator']);
        $roleSupertendent = Role::create(['name' => 'Supertendent']);
        $roleSupervisor = Role::create(['name' => 'Supervisor']);
        $roleStaff = Role::create(['name' => 'Staff']);

        // Menambahkan izin ke role Administrator
        $roleAdmin->givePermissionTo($permissions);

        // Menambahkan izin ke role Users (hanya terkait user)
        $roleSupertendent->givePermissionTo([
            'tambah-data',
            'edit-data',
            'lihat-data',
            'hapus-data',
        ]);

        $roleSupervisor->givePermissionTo([
            'tambah-data',
            'edit-data',
            'lihat-data',
            'hapus-data',
        ]);

        $roleStaff->givePermissionTo([
            'tambah-data',
            'edit-data',
            'lihat-data',
            'hapus-data',
        ]);

        // Membuat pengguna Administrator
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sbm.com',
            'password' => bcrypt('12345678'), // Ganti dengan password aman
        ]);

        $supertendent = User::create([
            'name' => 'Supertendent User',
            'email' => 'supertendent@sbm.com',
            'password' => bcrypt('12345678'), // Ganti dengan password aman
        ]);

        $supervisor = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@sbm.com',
            'password' => bcrypt('12345678'), // Ganti dengan password aman
        ]);

        // Membuat pengguna Users
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@sbm.com',
            'password' => bcrypt('12345678'), // Ganti dengan password aman
        ]);

        // Memberikan role kepada pengguna
        $admin->assignRole($roleAdmin);
        $supertendent->assignRole($roleSupertendent);
        $supervisor->assignRole($roleSupervisor);
        $staff->assignRole($roleStaff);

        // Jika ingin mencetak hasil di konsol
        $this->command->info('Role, permission, dan user berhasil dibuat!');
    }
}
