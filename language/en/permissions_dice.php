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
	'ACL_F_DICE_ROLL'			=> '<strong>Dice</strong> - Can roll dice',
	'ACL_F_DICE_EDIT'			=> '<strong>Dice</strong> - Can edit a rolled dice',
	'ACL_F_DICE_DELETE'			=> '<strong>Dice</strong> - Can delete own dice',
	'ACL_F_DICE_NO_LIMIT'		=> '<strong>Dice</strong> - Can ignore dice limit per post',
	'ACL_F_DICE_VIEW'			=> '<strong>Dice</strong> - Can view rolled dice',

	'ACL_F_MOD_DICE_ADD'		=> '<strong>Dice</strong> - <strong><em>Mod:</em></strong> Can roll dice on other users´s post',
	'ACL_F_MOD_DICE_EDIT'		=> '<strong>Dice</strong> - <strong><em>Mod:</em></strong> Can edit rolled dice on other users´s post',
	'ACL_F_MOD_DICE_DELETE'		=> '<strong>Dice</strong> - <strong><em>Mod:</em></strong> Can delete dice on other users´s post',

	'ACL_A_DICE_ADMIN'			=> '<strong>Dice</strong> - Can administer the extension',

	'ACL_U_DICE_USE_UCP'		=> '<strong>Dice</strong> - Can manage the Dice UCP',
	'ACL_U_DICE_TEST'			=> '<strong>Dice</strong> - Can use the “Test notation” page',
	'ACL_U_DICE_SKIN'			=> '<strong>Dice</strong> - Can ignore overriding forum skins<br><em>The user selected skin will be used, even when the forum skin is set to “override”.</em>',
]);
