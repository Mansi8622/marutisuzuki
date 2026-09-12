<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantModelTrait
{
    public static function bootMultiTenantModelTrait()
    {
        if (! app()->runningInConsole() && auth()->check()) {
            $user = auth()->user();
    
            // Only check roles if the method or relation exists
            $isAdmin = method_exists($user, 'roles') && $user->roles()?->exists()
                ? $user->roles->contains(1)
                : false;
    
            static::creating(function ($model) use ($isAdmin) {
                if (! $isAdmin) {
                    $model->created_by_id = auth()->id();
                }
            });
    
            if (! $isAdmin) {
                static::addGlobalScope('created_by_id', function (Builder $builder) {
                    $field = sprintf('%s.%s', $builder->getQuery()->from, 'created_by_id');
                    $builder->where($field, auth()->id())->orWhereNull($field);
                });
            }
        }
    }
    
}
