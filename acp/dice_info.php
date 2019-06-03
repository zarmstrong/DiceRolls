<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\acp;

/**
 * phpBB Studio's Dice ACP module info.
 */
class dice_info
{
	public function module()
	{
		return [
			'filename'	=> '\phpbbstudio\dice\acp\dice_module',
			'title'		=> 'ACP_DICE_CAT',
			'modes'		=> [
				'dashboard'	=> [
					'title'	=> 'ACP_DICE_DASH',
					'auth'	=> 'ext_phpbbstudio/dice && acl_a_dice_admin',
					'cat'	=> ['ACP_DICE_CAT'],
				],
			],
		];
	}
}
