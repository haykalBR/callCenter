<?php


namespace App\Domain\Membre\Entity;


interface RoleInterface
{

    /**
     * Role ID for super admin users; should match what's in the "role" table.
     */
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    /**
     * Checks if the role has a permission.
     *
     * @param string $permission
     * The permission to check for
     *
     * @return bool
     * TRUE if the role has the permission, FALSE if not
     */
    public function hasPermission($permission);
    /**
     * Indicates that a role has all available permissions.
     *
     * @return bool
     * TRUE if the role has all permissions
     */
    public function isSuperAdmin();
}