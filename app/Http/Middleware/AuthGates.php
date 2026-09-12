<?php
namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        // Get all roles with permissions
        $roles = Role::with('permissions')->get();
        $permissionsArray = [];

        // Loop through each role and its permissions to create a permissions array
        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissionsArray[$permission->title][] = $role->id;
            }
        }

        // Dynamically define gates for each permission title
        foreach ($permissionsArray as $title => $roles) {
            Gate::define($title, function ($user) use ($roles) {
                // Ensure that roles are available and prevent calling pluck() on null
                if ($user->roles) {
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                }

                return false;
            });
        }

        return $next($request);
    }
}
