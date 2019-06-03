<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

/**
* Some characters you may want to copy&paste: ’ » “ ” …
*/
$lang = array_merge($lang, [
	'UCP_DICE_TITLE'		=> 'Dice',
	'UCP_DICE'				=> 'Settings',

	'UCP_DICE_USER'			=> 'Dice mode',
	'UCP_DICE_USER_EXPLAIN'	=> 'Default display skin for dice rolls.',

	'UCP_DICE_SAVED'		=> 'Settings have been saved successfully!',
]);
