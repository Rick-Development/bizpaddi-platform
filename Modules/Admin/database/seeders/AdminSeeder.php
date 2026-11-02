<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Admin::where('email', 'admin@bizpaddi.com')->exists()) {
            Admin::create([
                'name' => 'Super Admin',
                'email' => 'admin@bizpaddi.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ]);
        }
    }
}
