<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * phpBB Studio's Dice Migration: Install text configuration.
 */
class install_config_text extends \phpbb\db\migration\migration
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
		return ['\phpbb\db\migration\data\v32x\v325'];
	}

	/**
	 * Add the dice extension text configurations to the database.
	 *
	 * @return array
	 * @access public
	 */
	public function update_data()
	{
		return [
			['config_text.add', ['dice_skins', json_encode(['bajahs_red', 'classic_steel'])]],
			['config_text.add', ['dice_sides', json_encode([4, 6, 8, 10, 12, 20])]],
		];
	}
}
