<?php

namespace TechlifyInc\LaravelRbac\Traits;

use TechlifyInc\LaravelRbac\Models\Role;

/**
 * Description of LaravelRbac
 *
 * @author 
 */
trait LaravelRbac
{

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function attachRole($role)
    {
        return $this->assignRole($role);
    }

    public function assignRole($role)
    {
        $slug = (is_string($role)) ? $role : $role->slug;

        return $this->roles()->save(
                        Role::where("slug", $slug)->firstOrFail()
        );
    }

    public function detachRole($role)
    {
        return $this->removeRole($role);
    }

    /**
     * Detach role from user.
     * @param int|Role $role
     */
    public function removeRole($role)
    {
        $slug = (is_string($role)) ? $role : $role->slug;
        $this->roles()->detach(Role::where("slug", $slug)->firstOrFail());
    }

    public function hasRole($role)
    {
        if (is_string($role))
        {
            return $this->roles->contains('slug', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    /**
     * Check if user has permission to current operation
     * @param string $slug
     * @return bool
     * 
     * @todo Make this more efficient
     */
    public function hasPermission($permission)
    {
        if (1 == $this->id)
        {
            return true;
        }
        $slug = (is_string($permission)) ? $permission : $permission->slug;

        foreach ($this->roles as $role)
        {
            if ($role->hasPermission($slug))
            {
                return true;
            }
        }

        return false;
    }

}
