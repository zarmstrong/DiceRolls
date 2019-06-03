<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * phpBB Studio's Dice Migration: Install ACP module.
 */
class install_ucp_module extends \phpbb\db\migration\migration
{
	/**
	 * Check if the migration is effectively installed (entirely optional).
	 *
	 * @return bool			True if this migration is installed, False if this migration is not installed
	 * @access public
	 */
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
				FROM ' . $this->table_prefix . "modules
				WHERE module_class = 'ucp'
					AND module_langname = 'UCP_DICE_TITLE'";
		$result = $this->db->sql_query($sql);
		$module_id = (bool) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		return $module_id !== false;
	}

	/**
	 * Assign migration file dependencies for this migration.
	 *
	 * @return array		Array of migration files
	 * @access public
	 * @static
	 */
	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v32x\v325'];
	}

	/**
	 * Add the dice extension modules to the database.
	 *
	 * @return array
	 * @access public
	 */
	public function update_data()
	{
		return [
			['module.add', [
				'ucp',
				0,
				'UCP_DICE_TITLE',
			]],
			['module.add', [
				'ucp',
				'UCP_DICE_TITLE',
				[
					'module_basename'	=> '\phpbbstudio\dice\ucp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
