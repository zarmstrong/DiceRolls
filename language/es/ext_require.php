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
	'ERROR_PHPBB_VERSION'	=> 'La versión mínima de phpBB requerida es %1$s pero menos de %2$s',
	'ERROR_PHP_VERSION'		=> 'La versión de PHP debe ser igual o mayor que 5.5',
	'ERROR_GLOB_STREAM'		=> 'El flujo GLOB no está disponible en su sistema.',
]);
