<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * Install dice tables and columns.
 */
class install_user_schema extends \phpbb\db\migration\migration
{
	/**
	 * Check if the migration is effectively installed (entirely optional).
	 *
	 * @return bool 		True if this migration is installed, False if this migration is not installed
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
		return array('\phpbb\db\migration\data\v32x\v325');
	}

	/**
	 * Add the dice extension schema to the database.
	 *
	 * @return array 		Array of table schema
	 * @access public
	 */
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'		=> array(
					'dice_enabled'			=>	array('BOOL', 0),					/* Dice forum (0 = No) */
					'dice_f_skin'			=>	array('VCHAR_UNI', 'bajahs_red'),	/* The skin to use as default */
					'dice_skin_override'	=>	array('BOOL', 0),					/* Override user's choice (0 = No) */
				),
				$this->table_prefix . 'users' => array(
					'dice_u_skin'			=> array('VCHAR_UNI', 'bajahs_red'),	/* The skin to use as default */
					'dice_u_roll_limit'		=> array('BOOL', 0),					/* Overrides forum permission */
				),
			),
			'add_tables'	=> array(
				$this->table_prefix . 'dice_rolls'	=> array(
					'COLUMNS'	=> array(
						'roll_num'			=> array('ULINT', null, 'auto_increment'),
						'roll_id'			=> array('ULINT', 0),
						'roll_notation'		=> array('VCHAR_UNI', ''),
						'roll_dices'		=> array('TEXT_UNI', ''),
						'roll_rolls'		=> array('TEXT_UNI', ''),
						'roll_output'		=> array('TEXT_UNI', ''),
						'roll_total'		=> array('ULINT', 0),
						'roll_successes'	=> array('USINT', 0),
						'roll_is_pool'		=> array('BOOL', 0),
						'roll_time'			=> array('TIMESTAMP', 0),
						'roll_edit_user'	=> array('ULINT', 0),
						'roll_edit_time'	=> array('TIMESTAMP', 0),
						'roll_edit_count'	=> array('ULINT', 0),
						'forum_id'			=> array('ULINT', 0),
						'topic_id'			=> array('ULINT', 0),
						'post_id'			=> array('ULINT', 0),
						'user_id'			=> array('ULINT', 0),
					),
					'PRIMARY_KEY'	=> 'roll_num',
					'KEYS'			=> array(
						'roll_id'	=> array('INDEX', 'roll_id'),
						'forum_id'	=> array('INDEX', 'forum_id'),
						'topic_id'	=> array('INDEX', 'topic_id'),
						'post_id'	=> array('INDEX', 'post_id'),
						'user_id'	=> array('INDEX', 'user_id'),
					),
				),
			),
		);
	}

	/**
	 * Drop the dice rolls schema from the database.
	 *
	 * @return array		Array of table schema
	 * @access public
	 */
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'dice_enabled',
					'dice_f_skin',
					'dice_skin_override',
				),
				$this->table_prefix . 'users'	=> array(
					'dice_u_skin',
					'dice_u_roll_limit',
				),
			),
			'drop_tables'	=> array(
				$this->table_prefix . 'dice_rolls',
			),
		);
	}
}
