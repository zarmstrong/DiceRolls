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
	// Cat
	'ACL_CAT_PHPBB_STUDIO'	=> 'phpBB Studio',

	// ACP forums
	'ACP_DICE_SETTINGS'					=> 'Dice settings',
	'ACP_DICE_ENABLE'					=> 'Enable dice',
	'ACP_DICE_ENABLE_DESC'				=> 'Enables the dice extension in this forum. The dice BBCode will only be enabled for users who have the permission to roll the dice in this forum.',
	'ACP_DICE_F_SKIN'					=> 'Dice skin',
	'ACP_DICE_F_SKIN_DESC'				=> 'The default dice skin for dice rolls in this forum.',
	'ACP_DICE_SKIN_OVERRIDE'			=> 'Override dice skin',
	'ACP_DICE_SKIN_OVERRIDE_DESC'		=> 'Replaces user’s dice skin with the skin as defined under “Dice skin”.',

	// ACP
	'ACP_DICE_CAT'						=> 'phpBB Studio - Dice',
	'ACP_DICE_DASH'						=> 'Diceboard',

	'ACP_DICE_SIDES'					=> 'Dice sides',
	'ACP_DICE_SIDES_SHORT'				=> 'Sides',

	'ACP_DICE_SKINS'					=> 'Dice skins',
	'ACP_DICE_SKINS_SHORT'				=> 'Skins',

	// Log
	'LOG_ACP_DICE_LOCATIONS'			=> '<strong>Altered dice link locations</strong>',
	'LOG_ACP_DICE_ORPHANED'				=> '<strong>Deleted orphaned dice rolls</strong><br />» %s deleted',
	'LOG_ACP_DICE_SETTINGS'				=> '<strong>Altered dice settings</strong>',
	'LOG_ACP_DICE_SIDE_ADD'				=> '<strong>Added new dice side</strong><br />» %s',
	'LOG_ACP_DICE_SIDE_DELETE'			=> '<strong>Deleted dice side</strong><br />» %s',
	'LOG_ACP_DICE_SKIN_INSTALL'			=> '<strong>Installed new dice skin</strong><br />» %s',
	'LOG_ACP_DICE_SKIN_UNINSTALL'		=> '<strong>Uninstalled dice skin</strong><br />» %s',
]);
