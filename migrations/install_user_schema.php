<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * phpBB Studio's Dice Migration: Install dice tables and columns.
 */
class install_user_schema extends \phpbb\db\migration\migration
{
	/**
	 * Check if the migration is effectively installed (entirely optional).
	 *
	 * @return bool			True if this migration is installed, False if this migration is not installed
	 * @access public
	 */
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'forums', 'dice_enabled');
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
	 * Add the dice extension schema to the database.
	 *
	 * @return array		Array of table schema
	 * @access public
	 */
	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'forums'		=> [
					'dice_enabled'			=>	['BOOL', 0],					/* Dice forum (0 = No) */
					'dice_f_skin'			=>	['VCHAR_UNI', 'bajahs_red'],	/* The skin to use as default */
					'dice_skin_override'	=>	['BOOL', 0],					/* Override user's choice (0 = No) */
				],
				$this->table_prefix . 'users' => [
					'dice_u_skin'			=> ['VCHAR_UNI', 'bajahs_red'],	/* The skin to use as default */
					'dice_u_roll_limit'		=> ['BOOL', 0],					/* Overrides forum permission */
				],
			],
			'add_tables'	=> [
				$this->table_prefix . 'dice_rolls'	=> [
					'COLUMNS'	=> [
						'roll_num'			=> ['ULINT', null, 'auto_increment'],
						'roll_id'			=> ['ULINT', 0],
						'roll_notation'		=> ['VCHAR_UNI', ''],
						'roll_dices'		=> ['TEXT_UNI', ''],
						'roll_rolls'		=> ['TEXT_UNI', ''],
						'roll_output'		=> ['TEXT_UNI', ''],
						'roll_total'		=> ['ULINT', 0],
						'roll_successes'	=> ['USINT', 0],
						'roll_is_pool'		=> ['BOOL', 0],
						'roll_time'			=> ['TIMESTAMP', 0],
						'roll_edit_user'	=> ['ULINT', 0],
						'roll_edit_time'	=> ['TIMESTAMP', 0],
						'roll_edit_count'	=> ['ULINT', 0],
						'forum_id'			=> ['ULINT', 0],
						'topic_id'			=> ['ULINT', 0],
						'post_id'			=> ['ULINT', 0],
						'user_id'			=> ['ULINT', 0],
					],
					'PRIMARY_KEY'	=> 'roll_num',
					'KEYS'			=> [
						'roll_id'	=> ['INDEX', 'roll_id'],
						'forum_id'	=> ['INDEX', 'forum_id'],
						'topic_id'	=> ['INDEX', 'topic_id'],
						'post_id'	=> ['INDEX', 'post_id'],
						'user_id'	=> ['INDEX', 'user_id'],
					],
				],
			],
		];
	}

	/**
	 * Drop the dice rolls schema from the database.
	 *
	 * @return array		Array of table schema
	 * @access public
	 */
	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				$this->table_prefix . 'forums'	=> [
					'dice_enabled',
					'dice_f_skin',
					'dice_skin_override',
				],
				$this->table_prefix . 'users'	=> [
					'dice_u_skin',
					'dice_u_roll_limit',
				],
			],
			'drop_tables'	=> [
				$this->table_prefix . 'dice_rolls',
			],
		];
	}
}
