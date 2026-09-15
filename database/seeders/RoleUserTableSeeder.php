<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();
        $role = Role::where('title', 'Admin')->firstOrFail();
        $admin->roles()->syncWithoutDetaching([$role->id]);
    }
}
