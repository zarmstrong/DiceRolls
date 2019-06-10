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
	'ACP_DICE_DICE'		=> [
		1 => '%d dice',	// One dice
		2 => '%d dice', // Two dice (don't we love the English language)
	],

	'ACP_DICE_ENJOY'				=> 'Enjoy',

	'ACP_DICE_EXAMPLE'				=> 'Example',
	'ACP_DICE_EXAMPLE_1'			=> 'There are a total of 3 rolls in this posts, which can be limited with <em>maximum rolls per post</em>',
	'ACP_DICE_EXAMPLE_2'			=> 'Each roll consists of multiple <em>(types of)</em> dice',
	'ACP_DICE_EXAMPLE_3'			=> 'Each roll has a total dice quantity',
	'ACP_DICE_EXAMPLE_4'			=> 'Each dice has a dice quantity',
	'ACP_DICE_EXAMPLE_5'			=> 'Each dice has sides',

	'ACP_DICE_INVALID'				=> 'Invalid',
	'ACP_DICE_VALID'				=> 'Valid',
	'ACP_DICE_VALID_ALL'			=> 'All installed skins are valid!',
	'ACP_DICE_VALID_NOT_ALL'		=> 'Not all installed skins are valid!',

	'ACP_DICE_INSTALLED'			=> 'Installed',
	'ACP_DICE_INSTALLED_NOT'		=> 'Not installed',
	'ACP_DICE_INSTALLED_IN'			=> 'Installation directory',

	'ACP_DICE_LOCATIONS'			=> 'Dice link locations',
	'ACP_DICE_LOCATIONS_DESC'		=> 'Select one or more locations where the link to the dice page should appear.',
	'ACP_DICE_LOCATIONS_EXPLAIN'	=> 'This is an example of a board index. In here you can select where you want the dice page link to show up. You can select as many locations as you like, from nowhere to at all places.<br>The dice page provides a dice tester, the dice limitations set by an Administrator and a dice FAQ. All is provided to fully understand all the dice possibilities.',
	'ACP_DICE_LOCATIONS_SUCCESS'	=> 'You have successfully altered the dice link locations.',

	'ACP_DICE_ORPHANED'				=> 'Delete orphaned rolls',
	'ACP_DICE_ORPHANED_CONFIRM'		=> 'Are you sure you wish to delete all orphaned dice rolls?<br>This will remove all rolls that do not belong to a forum, topic or post.<br>Users that are currently creating a post with a roll, will also have their roll deleted.',
	'ACP_DICE_ORPHANED_SUCCESS'		=> 'You have successfully deleted all orphaned rolls.',

	'ACP_DICE_ROLL_NR'				=> 'Roll %d', // Roll 1, Roll 2, etc..
	'ACP_DICE_ROLLS'				=> 'Dice rolls',
	'ACP_DICE_ROLLS_SHORT'			=> 'Rolls',
	'ACP_DICE_ROLLS_DB'				=> 'Rolls in the database',
	'ACP_DICE_ROLLS_NONE'			=> 'There are no dice rolls.',

	'ACP_DICE_SETTINGS_EXAMPLE'		=> 'Click here to view the example.',
	'ACP_DICE_SETTINGS_EXPLAIN'		=> 'Terminology in this extension can be rather tricky, as a lot of things potentially have the same name. Therefore we have created an example to better illustrate what is what.',

	'ACP_DICE_SIDE_ADD'				=> 'Add a side',
	'ACP_DICE_SIDE_ADD_CONFIRM'		=> 'Are you sure you wish to add this dice side?',
	'ACP_DICE_SIDE_ADD_SUCCESS'		=> 'You have successfully added <strong>%s</strong> as a dice side.',
	'ACP_DICE_SIDE_DELETE'			=> 'Delete side',
	'ACP_DICE_SIDE_DELETE_CONFIRM'	=> 'Are you sure you wish to delete this dice side?',
	'ACP_DICE_SIDE_DELETE_SUCCESS'	=> 'You have successfully deleted <strong>%s</strong> as a dice side.',
	'ACP_DICE_SIDES_AVAILABLE'		=> 'Available sides',
	'ACP_DICE_SIDES_EXPLAIN'		=> 'The allowed sides users can use in their dice notations. Skins are valid when they have images for all sides provided here. For example, sides: <small><samp>4, 5</samp></small>. Images: <small><samp>d4_1 to d4_4, d5_1 to d5_5</samp></small>',
	'ACP_DICE_SIDES_NONE'			=> 'There are no dice sides.',
	'ACP_DICE_SIDES_ONLY'			=> 'Only available sides',
	'ACP_DICE_SIDES_ONLY_DESC'		=> 'If this setting is enabled, users are only allowed to use the available dice sides.',
	'ACP_DICE_SIDES_ONLY_STATS'		=> 'Users can only use available dice sides.',
	'ACP_DICE_SIDES_ONLY_UPTO'		=> 'Users can use upto %d dice sides.',
	'ACP_DICE_SIDES_ONLY_UNLIMITED'	=> 'Users can use unlimited dice sides.',

	'ACP_DICE_SKIN_INSTALL'				=> 'Install skin',
	'ACP_DICE_SKIN_INSTALL_CONFIRM'		=> 'Are you sure you wish to install this dice skin?',
	'ACP_DICE_SKIN_INSTALL_SUCCESS'		=> 'You have successfully installed <strong>%s</strong> as a dice skin.',
	'ACP_DICE_SKIN_UNINSTALL'			=> 'Uninstall skin',
	'ACP_DICE_SKIN_UNINSTALL_CONFIRM'	=> 'Are you sure you wish to uninstall this dice skin?',
	'ACP_DICE_SKIN_UNINSTALL_SUCCESS'	=> 'You have successfully uninstalled <strong>%s</strong> as a dice skin.',
	'ACP_DICE_SKINS_AVAILABLE'			=> 'Available skins',
	'ACP_DICE_SKINS_INSTALLED'			=> 'Installed skins',
	'ACP_DICE_SKINS_EXPLAIN'			=> 'Skins are automatically found when uploaded to the designated directory. Images should be correctly named. For example, for a 4-sided dice: <small><samp>d4_1.gif, d4_2.gif, d4_3.gif, d4_4.gif</samp></small>',
	'ACP_DICE_SKINS_NONE'				=> 'There are no dice skins.',

	'ACP_DICE_SUMMARY'				=> 'Summary',

	'ACP_DICE_TOP_TOPICS'			=> 'Top topics',
	'ACP_DICE_TOP_TOPICS_DESC'		=> 'List of topics with the most rolls.',
	'ACP_DICE_TOP_USERS'			=> 'Top users',
	'ACP_DICE_TOP_USERS_DESC'		=> 'List of users with the most rolls.',

	'ACP_DICE_SKINS_DIR'				=> 'Skin image directory',
	'ACP_DICE_SKINS_DIR_DESC'			=> 'This path will be used to search skins. Changing this will reset the installed skins.<br><small>Path under your phpBB root directory, e.g. <samp>images/skins</samp>.</small>',
	'ACP_DICE_SKINS_PATH_ERROR'			=> 'The skins directory you entered is invalid.<br>The value contains the following unsupported characters:<br />%s',
	'ACP_DICE_SKINS_IMG_HEIGHT'			=> 'Skin image height',
	'ACP_DICE_SKINS_IMG_HEIGHT_DESC'	=> 'Image height for the dice skin images. Has to be between 16 and 80 pixels.',
	'ACP_DICE_SKINS_IMG_HEIGHT_ERROR'	=> 'The image height you entered is invalid. The value has to be between 16 and 80 pixels.',
	'ACP_DICE_SKINS_IMG_WIDTH'			=> 'Skin image width',
	'ACP_DICE_SKINS_IMG_WIDTH_DESC'		=> 'Image width for the dice skin images. Has to be between 16 and 80 pixels.',
	'ACP_DICE_SKINS_IMG_WIDTH_ERROR'	=> 'The image width you entered is invalid. The value has to be between 16 and 80 pixels.',

	'ACP_DICE_ZERO_UNLIMITED'		=> 'Set value to <strong>0</strong> for an unlimited amount.',

	// Settings
	'ACP_DICE_MAX_ROLLS'							=> 'Maximum rolls per post',
	'ACP_DICE_MAX_ROLLS_DESC'						=> 'The maximum number of rolls that can be added per post.',
	'ACP_DICE_PER_NOTATION'							=> 'Maximum dice per roll',
	'ACP_DICE_PER_NOTATION_DESC'					=> 'The following roll has 2 dice: 5d6 <strong class="error">+</strong> 2d4',
	'ACP_DICE_QTY_PER_DICE'							=> 'Maximum dice quantity per roll',
	'ACP_DICE_QTY_PER_DICE_DESC'					=> 'The following roll has a total dice quantity of 7: <strong class="error">5</strong>d6 + <strong class="error">2</strong>d4',
	'ACP_DICE_QTY_DICE_PER_NOTATION'				=> 'Maximum dice quantity per dice',
	'ACP_DICE_QTY_DICE_PER_NOTATION_DESC'			=> 'The following roll has 2 dice, both with a dice quantity of 3: <strong class="error">3</strong>d6 + <strong class="error">3</strong>d4',
	'ACP_DICE_SIDES_PER_DICE'						=> 'Maximum sides per dice',
	'ACP_DICE_SIDES_PER_DICE_DESC'					=> 'The following roll has 1 dice with 10 sides: 4d<strong class="error">10</strong>',
	'ACP_DICE_PC_DICE_PER_NOTATION'					=> 'Maximum percentage dice per roll',
	'ACP_DICE_PC_DICE_PER_NOTATION_DESC'			=> 'The following roll has 2 percentage dice: 6d100 <strong class="error">+</strong> 3d%',
	'ACP_DICE_FUDGE_DICE_PER_NOTATION'				=> 'Maximum fudge dice per roll',
	'ACP_DICE_FUDGE_DICE_PER_NOTATION_DESC'			=> 'The following roll has 1 fudge dice: 2d<strong class="error">F.2</strong> + 4d8',
	'ACP_DICE_EXPLODING_DICE_PER_NOTATION'			=> 'Maximum exploding dice per roll',
	'ACP_DICE_EXPLODING_DICE_PER_NOTATION_DESC'		=> 'The following roll has 1 exploding dice: 2d6<strong class="error">!</strong> + 4d8',
	'ACP_DICE_PENETRATION_DICE_PER_NOTATION'		=> 'Maximum penetrating dice per roll',
	'ACP_DICE_PENETRATION_DICE_PER_NOTATION_DESC'	=> 'The following roll has 1 penetrating dice: 2d6<strong class="error">!p</strong> + 2d%',
	'ACP_DICE_COMPOUND_DICE_PER_NOTATION'			=> 'Maximum compounding dice per roll',
	'ACP_DICE_COMPOUND_DICE_PER_NOTATION_DESC'		=> 'The following roll has 1 compounding dice: 2d6<strong class="error">!!</strong> + 5d4',
]);
