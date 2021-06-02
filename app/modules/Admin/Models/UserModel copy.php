<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table      = 'users';
	protected $primaryKey = 'id';
	protected $returnType = '\App\Entities\Userentity';
	protected $allowedFields = ['username', 'email','password_hash','active'];
	const ORDERABLE = [
		1 => 'username',
		2 => 'email',
		4 => 'created_at',
	];

	/**
	 * Get resource data.
	 *
	 * @param string $search
	 *
	 * @return \CodeIgniter\Database\BaseBuilder
	 */
	public function getResource(string $search = '')
	{
		$builder = $this->builder()
			->select('id,username, email, active, created_at');
		$condition = empty($search)
			? $builder
			: $builder->groupStart()
			->like('email', $search)
			->orLike('email', $search)
			->groupEnd();
		return $condition;
	}
}
