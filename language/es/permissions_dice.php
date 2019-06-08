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
	'ACL_F_DICE_ROLL'			=> '<strong>Dados</strong> - Puede tirar dados',
	'ACL_F_DICE_EDIT'			=> '<strong>Dados</strong> - Puede editar una tirada de dado',
	'ACL_F_DICE_DELETE'			=> '<strong>Dados</strong> - Puede borrar dados propios',
	'ACL_F_DICE_NO_LIMIT'		=> '<strong>Dados</strong> - Puede ignorar el límite de dados por mensaje',
	'ACL_F_DICE_VIEW'			=> '<strong>Dados</strong> - Puede ver tirada de dados',

	'ACL_F_MOD_DICE_ADD'		=> '<strong>Dados</strong> - <strong><em>Mod:</em></strong> Puede lanzar dados en el mensaje de otros usuarios',
	'ACL_F_MOD_DICE_EDIT'		=> '<strong>Dados</strong> - <strong><em>Mod:</em></strong> Puede editar tirada de dados en mensajes de otros usuarios',
	'ACL_F_MOD_DICE_DELETE'		=> '<strong>Dados</strong> - <strong><em>Mod:</em></strong> Puede borrar dados en mensajes de otros usuarios',

	'ACL_A_DICE_ADMIN'			=> '<strong>Dados</strong> - Puede administrar la extensión',

	'ACL_U_DICE_USE_UCP'		=> '<strong>Dados</strong> - Puede gestionar el PCU Dados',
	'ACL_U_DICE_TEST'			=> '<strong>Dados</strong> - Puede usar la página “Notación de Prueba”',
	'ACL_U_DICE_SKIN'			=> '<strong>Dados</strong> - Puede ignorar las skins de foro anulantes<br><em>Se utilizará la skin seleccionada por el usuario, incluso cuando la skin del foro esté configurada para “anular”.</em>',
]);
