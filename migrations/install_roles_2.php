<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * phpBB Studio's Dice Migration: Install permission roles.
 */
class install_roles_2 extends \phpbb\db\migration\migration
{
	/**
	 * Assign migration file dependencies for this migration.
	 *
	 * @return array		Array of migration files
	 * @access public
	 * @static
	 */
	static public function depends_on()
	{
		return [
			'\phpbb\db\migration\data\v32x\v325',
			'\phpbbstudio\dice\migrations\install_roles',
		];
	}

	/**
	 * Add the dice extension permissions to the database.
	 *
	 * @return array		Array of permissions
	 * @access public
	 */
	public function update_data()
	{
		$data = [];

		/* Forum User permissions */
		if ($this->role_exists('ROLE_FORUM_STANDARD'))
		{
			$data[] = ['permission.permission_unset', ['ROLE_FORUM_STANDARD', 'f_dice_edit']];
			$data[] = ['permission.permission_unset', ['ROLE_FORUM_STANDARD', 'f_dice_delete']];
		}

		return $data;
	}

	/**
	 * Checks whether the given role does exist or not.
	 *
	 * @param  string		$role		The name of the role
	 * @return bool						true if the role exists, false otherwise
	 * @access protected
	 */
	protected function role_exists($role)
	{
		$sql = 'SELECT role_id
				FROM ' . $this->table_prefix . "acl_roles
				WHERE role_name = '" . $this->db->sql_escape($role) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$role_id = $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);

		return (bool) $role_id;
	}
}
