<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * phpBB Studio's Dice Migration: Install configuration.
 */
class install_configs extends \phpbb\db\migration\migration
{
	/**
	 * Check if the migration is effectively installed (entirely optional).
	 *
	 * @return bool			True if this migration is installed, False if this migration is not installed
	 * @access public
	 */
	public function effectively_installed()
	{
		return isset($this->config['dice_version']);
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
	 * Add the dice extension configurations to the database.
	 *
	 * @return array		Array of configs
	 * @access public
	 */
	public function update_data()
	{
		return [
			['config.add', ['dice_sides_only', '1']],							/* real dice (bool) */
			['config.add', ['dice_version', '2.0.0-beta']],						/* Dice version */

			['config.add', ['dice_skins_dir', 'ext/phpbbstudio/dice/skins']],	/* skin directory relative to root */

			['config.add', ['dice_skins_img_height', 48]],						/* skin image height */
			['config.add', ['dice_skins_img_width', 48]],						/* skin image width */

			['config.add', ['dice_max_rolls', '20']],							/* rolls limit per post - notations */
			['config.add', ['dice_per_notation', '0']],							/* (eg 2 in 1d6+3d4) */
			['config.add', ['dice_qty_per_dice', '0']],							/* (eg the 2 in 2d6) */
			['config.add', ['dice_qty_dice_per_notation', '0']],				/* (eg 5 in 2d6+3d4) */
			['config.add', ['dice_sides_per_dice', '0']],						/* Sides */
			['config.add', ['dice_pc_dice_per_notation', '0']],					/* Percentile */
			['config.add', ['dice_fudge_dice_per_notation', '0']],				/* Fudge */
			['config.add', ['dice_penetration_dice_per_notation', '0']],		/* Penetration */
			['config.add', ['dice_compound_dice_per_notation', '0']],			/* Compound */
			['config.add', ['dice_exploding_dice_per_notation', '0']],			/* Explode */
			['config.add', ['dice_rolls_per_dice', '0']],						/* (include/exclude compounding rolls) */
			['config.add', ['dice_rolls_per_notation', '0']],					/* (include/exclude compounding rolls) */

			['config.add', ['dice_link_locations', 8]],							/* Dice link locations */
		];
	}
}
