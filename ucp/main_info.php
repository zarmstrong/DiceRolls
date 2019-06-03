<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\ucp;

/**
 *  phpBB Studio's Dice UCP module info.
 */
class main_info
{
	function module()
	{
		return [
			'filename'	=> '\phpbbstudio\dice\ucp\main_module',
			'title'		=> 'UCP_DICE_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'UCP_DICE',
					'auth'	=> 'ext_phpbbstudio/dice && acl_u_dice_use_ucp',
					'cat'	=> ['UCP_DICE_TITLE'],
				],
			],
		];
	}
}
