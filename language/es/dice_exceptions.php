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
	'EXCEPTION_OUT_OF_BOUNDS'		=> 'El campo `<strong>%1$s</strong>` recibió datos más allá de sus límites. Razón: %2$s',
	'EXCEPTION_UNEXPECTED_VALUE'	=> 'El campo `<strong>%1$s</strong>` recibió datos inesperados. Razón: %2$s',

	'EXCEPTION_ROLL_EDIT_COUNT'		=> 'Editar cantidad de tiradas',
	'EXCEPTION_ROLL_EDIT_TIME'		=> 'Editar fecha de tiradas',
	'EXCEPTION_ROLL_EDIT_USER'		=> 'Editar identificador del usuario',
	'EXCEPTION_ROLL_DICE_COMPOUND'	=> 'Dados compuestos (<span class="error">!!</span>)',
	'EXCEPTION_ROLL_DICE_EXPLODE'	=> 'Dados explosivos (<span class="error">!, !!, !p</span>)',
	'EXCEPTION_ROLL_DICE_FUDGE'		=> 'Dados de dulce de azúcar (<span class="error">F</span>)',
	'EXCEPTION_ROLL_DICE_PENETRATE'	=> 'Dados penetrantes (<span class="error">!p</span>)',
	'EXCEPTION_ROLL_DICE_PERCENT'	=> 'Porcentaje de dados (d<span class="error">%</span> o d<span class="error">100</span>)',
	'EXCEPTION_ROLL_DICE_QTY'		=> 'Cantidad de dados (<span class="error">X</span>d6)',
	'EXCEPTION_ROLL_DICES'			=> 'Dados',
	'EXCEPTION_ROLL_DICES_QTY'		=> 'Cantidad de dados (<span class="error">X</span>d6 + <span class="error">X</span>d6)',
	'EXCEPTION_ROLL_FORUM_ID'		=> 'Identificador de foro',
	'EXCEPTION_ROLL_ID'				=> 'Identificador de tirada',
	'EXCEPTION_ROLL_NOTATION'		=> 'Notación de tirada',
	'EXCEPTION_ROLL_POST_ID'		=> 'Identificador de mensaje',
	'EXCEPTION_ROLL_REGEX_NAME'		=> 'Nombre Regex',
	'EXCEPTION_ROLL_SIDES'			=> 'Lados del dado (1d<span class="error">X</span>)',
	'EXCEPTION_ROLL_TIME'			=> 'Fecha de tiradas',
	'EXCEPTION_ROLL_TOPIC_ID'		=> 'Identificador de tema',
	'EXCEPTION_ROLL_USER_ID'		=> 'Identificador de usuario',

	'EXCEPTION_FIELD_MISSING'		=> 'Falta el campo requerido.',
	'EXCEPTION_NOT_ALLOWED'			=> 'La entrada no está permitida.',
	'EXCEPTION_ROLL_ALREADY_EXIST'	=> 'La tirada proporcionada ya existe.',
	'EXCEPTION_ROLL_NO_MATCHES'		=> 'No se encontraron coincidencias de dados para la notación proporcionada.',
	'EXCEPTION_ROLL_NOT_EXIST'		=> 'La tirada proporcionada no existe.',
	'EXCEPTION_ROLL_NAME_NOT_FOUND'	=> 'El nombre regex proporcionado no se pudo encontrar.',

	// TRANSLATORS pay attention here
	'EXCEPTION_ROLL_ULINT'			=> 'La entrada no está en la rango de 0 a 4&#8239;294&#8239;967&#8239;295.', // Leave &#8239; in place (non-breaking thin space)
	'EXCEPTION_ROLL_USINT'			=> 'La entrada no está en la rango de 0 a 65&#8239;535.', // Leave &#8239; in place (non-breaking thin space)

	'EXCEPTION_TOO_LONG'			=> 'La entrada era más larga que la longitud máxima.',
	'EXCEPTION_TOO_HIGH'			=> 'La entrada fue mayor que el valor máximo.',
]);
