<?php

namespace App\Entities;


use CodeIgniter\Entity;

class Userentity extends Entity
{
    // protected $datamap = [
    //     'username' => 'email',
    // ];
    /**
     * Return data to colection map datatable.
     *
     * @param array $data
     * @param int   $recordsTotal
     * @param int   $recordsFiltered
     *
     * @return array
     */

    public static function datatable(array $data, int $recordsTotal, int $recordsFiltered)
    {
        return [
            'draw'            => service('request')->getGet('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ];
    }
    public function setPassword_hash(string $pass)
    {
        $this->attributes['password_hash'] = password_hash($pass, PASSWORD_BCRYPT);

        return $this;
    }
    /**
     * Activate user.
     *
     * @return $this
     */
    public function activate()
    {
        $this->attributes['active'] = 1;
        $this->attributes['activate_hash'] = null;

        return $this;
    }

    /**
     * Unactivate user.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->attributes['active'] = 0;

        return $this;
    }

    /**
     * Checks to see if a user is active.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return isset($this->attributes['active']) && $this->attributes['active'] == true;
    }

	/**
	 * Bans a user.
	 *
	 * @param string $reason
	 *
	 * @return $this
	 */
	public function ban(string $reason)
	{
		$this->attributes['status'] = 'banned';
		$this->attributes['status_message'] = $reason;

		return $this;
	}

	/**
	 * Removes a ban from a user.
	 *
	 * @return $this
	 */
	public function unBan()
	{
		$this->attributes['status'] = $this->status_message = '';

		return $this;
	}

	/**
	 * Checks to see if a user has been banned.
	 *
	 * @return bool
	 */
	public function isBanned(): bool
	{
		return isset($this->attributes['status']) && $this->attributes['status'] === 'banned';
	}

    /**
     * Determines whether the user has the appropriate permission,
     * either directly, or through one of it's groups.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can(string $permission)
    {
        return in_array(strtolower($permission), $this->getPermissions());
	}

    /**
     * Returns the user's permissions, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id=> name,
     * ]
     *
     * @return array|mixed
     */
    public function getPermissions()
    {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting permissions.');
        }

        if (empty($this->permissions))
        {
            $this->permissions = model(PermissionModel::class)->getPermissionsForUser($this->id);
        }

        return $this->permissions;
    }

    /**
     * Returns the user's roles, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id => name,
     * ]
     *
     * @return array|mixed
     */
    public function getRoles()
    {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting roles.');
        }

        if (empty($this->roles))
        {
            $groups = model(GroupModel::class)->getGroupsForUser($this->id);

            foreach ($groups as $group)
            {
                $this->roles[$group['group_id']] = strtolower($group['name']);
            }
        }

        return $this->roles;
	}

    /**
     * Warns the developer it won't work, so they don't spend
     * hours tracking stuff down.
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions = null)
    {
        throw new \RuntimeException('User entity does not support saving permissions directly.');
	}
}
