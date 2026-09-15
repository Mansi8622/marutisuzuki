<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class OfferPermissionSeeder extends Seeder
{
    public function run()
    {
        $ids = collect(['offer_create','offer_edit','offer_show','offer_delete','offer_access'])->map(fn ($title) => Permission::firstOrCreate(['title' => $title])->id);
        if ($admin = Role::where('title', 'Admin')->first()) $admin->permissions()->syncWithoutDetaching($ids);
    }
}
