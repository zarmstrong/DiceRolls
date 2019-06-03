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
	'EXCEPTION_OUT_OF_BOUNDS'		=> 'The field `<strong>%1$s</strong>` received data beyond its bounds. Reason: %2$s',
	'EXCEPTION_UNEXPECTED_VALUE'	=> 'The field `<strong>%1$s</strong>` received unexpected data. Reason: %2$s',

	'EXCEPTION_ROLL_EDIT_COUNT'		=> 'Roll edit count',
	'EXCEPTION_ROLL_EDIT_TIME'		=> 'Roll edit time',
	'EXCEPTION_ROLL_EDIT_USER'		=> 'Edit user identifier',
	'EXCEPTION_ROLL_DICE_COMPOUND'	=> 'Compounding dice (<span class="error">!!</span>)',
	'EXCEPTION_ROLL_DICE_EXPLODE'	=> 'Exploding dice (<span class="error">!, !!, !p</span>)',
	'EXCEPTION_ROLL_DICE_FUDGE'		=> 'Fudge dice (<span class="error">F</span>)',
	'EXCEPTION_ROLL_DICE_PENETRATE'	=> 'Penetrating dice (<span class="error">!p</span>)',
	'EXCEPTION_ROLL_DICE_PERCENT'	=> 'Percentage dice (d<span class="error">%</span> or d<span class="error">100</span>)',
	'EXCEPTION_ROLL_DICE_QTY'		=> 'Dice quantity (<span class="error">X</span>d6)',
	'EXCEPTION_ROLL_DICES'			=> 'Dices',
	'EXCEPTION_ROLL_DICES_QTY'		=> 'Dice quantities (<span class="error">X</span>d6 + <span class="error">X</span>d6)',
	'EXCEPTION_ROLL_FORUM_ID'		=> 'Forum identifier',
	'EXCEPTION_ROLL_ID'				=> 'Roll identifier',
	'EXCEPTION_ROLL_NOTATION'		=> 'Roll notation',
	'EXCEPTION_ROLL_POST_ID'		=> 'Post identifier',
	'EXCEPTION_ROLL_REGEX_NAME'		=> 'Regex name',
	'EXCEPTION_ROLL_SIDES'			=> 'Dice sides (1d<span class="error">X</span>)',
	'EXCEPTION_ROLL_TIME'			=> 'Roll time',
	'EXCEPTION_ROLL_TOPIC_ID'		=> 'Topic identifier',
	'EXCEPTION_ROLL_USER_ID'		=> 'User identifier',

	'EXCEPTION_FIELD_MISSING'		=> 'Required field missing.',
	'EXCEPTION_NOT_ALLOWED'			=> 'The input is not allowed.',
	'EXCEPTION_ROLL_ALREADY_EXIST'	=> 'The provided roll already exists.',
	'EXCEPTION_ROLL_NO_MATCHES'		=> 'No dice matches found for provided notation.',
	'EXCEPTION_ROLL_NOT_EXIST'		=> 'The provided roll does not exist.',
	'EXCEPTION_ROLL_NAME_NOT_FOUND'	=> 'The provided regex name could not be found.',

	// TRANSLATORS pay attention here
	'EXCEPTION_ROLL_ULINT'			=> 'The input is not in the rage of 0 to 4&#8239;294&#8239;967&#8239;295.', // Leave &#8239; in place (non-breaking thin space)
	'EXCEPTION_ROLL_USINT'			=> 'The input is not in the rage of 0 to 65&#8239;535.', // Leave &#8239; in place (non-breaking thin space)

	'EXCEPTION_TOO_LONG'			=> 'The input was longer than the maximum length.',
	'EXCEPTION_TOO_HIGH'			=> 'The input was higher than the maximum value.',
]);
