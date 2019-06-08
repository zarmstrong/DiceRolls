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
	'UCP_DICE_TITLE'		=> 'Dados',
	'UCP_DICE'				=> 'Ajustes',

	'UCP_DICE_USER'			=> 'Modo dados',
	'UCP_DICE_USER_EXPLAIN'	=> 'Pantalla de visualización predeterminada para tiradas de dados.',

	'UCP_DICE_SAVED'		=> '¡Ajustes guardados correctamente!',
]);
