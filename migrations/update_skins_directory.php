<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * phpBB Studio's Dice Migration: Update skins directory.
 */
class update_skins_directory extends \phpbb\db\migration\migration
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
			'\phpbbstudio\dice\migrations\install_configs',
		];
	}

	/**
	 * Update the dice skin directory configuration.
	 *
	 * @return array
	 * @access public
	 */
	public function update_data()
	{
		return [
			['config.remove', ['dice_version']],

			['if', [
				$this->config['dice_skins_dir'] === 'ext/phpbbstudio/dice/skins',
				['config.update', ['dice_skins_dir', 'images/dice']],
			]],
			['custom', [[$this, 'mirror_skin_directory']]],
		];
	}

	/**
	 * Mirror the dice skin directory.
	 *
	 * @throws \Exception
	 * @return void
	 * @access public
	 */
	public function mirror_skin_directory()
	{
		global $phpbb_container;

		/** @var \phpbb\filesystem\filesystem $filesystem */
		$filesystem = $phpbb_container->get('filesystem');

		$origin = $this->phpbb_root_path . 'ext/phpbbstudio/dice/skins';
		$target = $this->phpbb_root_path . 'images/dice';

		if (!$filesystem->exists($target))
		{
			$filesystem->mirror($origin, $target, null, [false, false, false]);
		}
	}
}
