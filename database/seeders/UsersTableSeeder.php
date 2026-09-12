<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'                  => 1,
                'name'                => 'Admin',
                'email'               => 'admin@admin.com',
                'password'            => bcrypt('password'),
                'remember_token'      => null,
                'approved'            => 1,
                'phone'               => '',
                'business_name'       => '',
                'gst_number'          => '',
                'pan_number'          => '',
                'bank_name'           => '',
                'account_number'      => '',
                'ifsc_code'           => '',
                'account_holder_name' => '',
                'license_details'     => '',
                'vendor'              => '',
            ],
        ];

        User::insert($users);
    }
}
