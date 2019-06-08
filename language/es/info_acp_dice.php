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
	'ACP_DICE_SETTINGS'					=> 'Ajustes de dados',
	'ACP_DICE_ENABLE'					=> 'Habilitar dados',
	'ACP_DICE_ENABLE_DESC'				=> 'Habilita la extensión de dados en este foro. El BBCode de dados solo se habilitará para los usuarios que tengan permiso de lanzar los dados en este foro.',
	'ACP_DICE_F_SKIN'					=> 'Skin de dados',
	'ACP_DICE_F_SKIN_DESC'				=> 'La skin de dados predeterminada para tiradas de dados en este foro.',
	'ACP_DICE_SKIN_OVERRIDE'			=> 'Anular skin de dados',
	'ACP_DICE_SKIN_OVERRIDE_DESC'		=> 'Reemplaza la skin de los dados del usuario con la skin como se define en “Skin de dados”.',

	// ACP
	'ACP_DICE_CAT'						=> 'phpBB Studio - Dados',
	'ACP_DICE_DASH'						=> 'Foro de Dados',

	'ACP_DICE_SIDES'					=> 'Lados de dados',
	'ACP_DICE_SIDES_SHORT'				=> 'Lados',

	'ACP_DICE_SKINS'					=> 'Skins de dados',
	'ACP_DICE_SKINS_SHORT'				=> 'Skins',

	// Log
	'LOG_ACP_DICE_LOCATIONS'			=> '<strong>Ubicaciones de enlace de dados alteradas</strong>',
	'LOG_ACP_DICE_ORPHANED'				=> '<strong>Tiradas de dados huérfanas eliminadas</strong><br />» %s borrado',
	'LOG_ACP_DICE_SETTINGS'				=> '<strong>Ajustes de dados alteradas</strong>',
	'LOG_ACP_DICE_SIDE_ADD'				=> '<strong>Nuevo lado de dado añadido</strong><br />» %s',
	'LOG_ACP_DICE_SIDE_DELETE'			=> '<strong>Lado de dado borrada</strong><br />» %s',
	'LOG_ACP_DICE_SKIN_INSTALL'			=> '<strong>Nueva skin de dado instalada</strong><br />» %s',
	'LOG_ACP_DICE_SKIN_UNINSTALL'		=> '<strong>Skin de dado desinstalada</strong><br />» %s',
]);
