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
		1 => '%d dado',	// One dice
		2 => '%d dados', // Two dice (don't we love the English language)
	],

	'ACP_DICE_ENJOY'				=> 'Disfrute',

	'ACP_DICE_EXAMPLE'				=> 'Ejemplo',
	'ACP_DICE_EXAMPLE_1'			=> 'Hay un total de 3 tiradas en este mensaje, que pueden limitarse con <em>Máximas tiradas por mensaje</em>',
	'ACP_DICE_EXAMPLE_2'			=> 'Cada tirada consta de múltiples <em>(tipos de)</em> dados',
	'ACP_DICE_EXAMPLE_3'			=> 'Cada tirada tiene una cantidad total de dados',
	'ACP_DICE_EXAMPLE_4'			=> 'Cada dado tiene una cantidad de dados',
	'ACP_DICE_EXAMPLE_5'			=> 'Cada dado tiene lados',

	'ACP_DICE_INVALID'				=> 'Inválida',
	'ACP_DICE_VALID'				=> 'Válida',
	'ACP_DICE_VALID_ALL'			=> '¡Todas las skins instaladas son válidas!',
	'ACP_DICE_VALID_NOT_ALL'		=> '¡No todas las skins instaladas son válidas!',

	'ACP_DICE_INSTALLED'			=> 'Instalado',
	'ACP_DICE_INSTALLED_NOT'		=> 'No instalado',
	'ACP_DICE_INSTALLED_IN'			=> 'Directorio de instalación',

	'ACP_DICE_LOCATIONS'			=> 'Ubicaciones de enlace de dados',
	'ACP_DICE_LOCATIONS_DESC'		=> 'Seleccione una o más ubicaciones donde debería aparecer el enlace a la página de dados.',
	'ACP_DICE_LOCATIONS_EXPLAIN'	=> 'Este es un ejemplo de un índice de foro. Aquí puede seleccionar dónde desea que aparezca el enlace de la página de dados. Puede seleccionar tantos lugares como desee, desde nada a cualquier lugar.<br>Las limitaciones de dados establecidas por un Administrador y el FAQ sobre los dados. Todo se proporciona para comprender completamente todas las posibilidades de los dados.',
	'ACP_DICE_LOCATIONS_SUCCESS'	=> 'Ha alterado correctamente las ubicaciones de enlace de dados.',

	'ACP_DICE_ORPHANED'				=> 'Eliminar tiradas huérfanas',
	'ACP_DICE_ORPHANED_CONFIRM'		=> '¿Está seguro de que desea eliminar todas las tiradas de dados huérfanas?<br>Esto eliminará todas las tiradas que no pertenecen a un foro, tema o mensaje.<br>Los usuarios que actualmente están creando un mensaje con una tirada, también tendrán su tirada eliminada.',
	'ACP_DICE_ORPHANED_SUCCESS'		=> 'Ha eliminado correctamente todas las tiradas huérfanas.',

	'ACP_DICE_ROLL_NR'				=> 'Tirada %d', // Roll 1, Roll 2, etc..
	'ACP_DICE_ROLLS'				=> 'Tiradas de dados',
	'ACP_DICE_ROLLS_SHORT'			=> 'Tiradas',
	'ACP_DICE_ROLLS_DB'				=> 'Tiradas en la base de datos',
	'ACP_DICE_ROLLS_NONE'			=> 'No hay tiradas de dados.',

	'ACP_DICE_SETTINGS_EXAMPLE'		=> 'Clic aquí para ver el ejemplo.',
	'ACP_DICE_SETTINGS_EXPLAIN'		=> 'La terminología en esta extensión puede ser bastante complicada, ya que muchas cosas tienen potencialmente el mismo nombre. Por eso hemos creado un ejemplo para ilustrar mejor, qué es qué.',

	'ACP_DICE_SIDE_ADD'				=> 'Añadir un lado',
	'ACP_DICE_SIDE_ADD_CONFIRM'		=> '¿Está seguro de que desea añadir este lado de los dados?',
	'ACP_DICE_SIDE_ADD_SUCCESS'		=> 'Ha añadido correctamente <strong>%s</strong> como un lado de dados.',
	'ACP_DICE_SIDE_DELETE'			=> 'Borrar lado',
	'ACP_DICE_SIDE_DELETE_CONFIRM'	=> '¿Está seguro de que desea eliminar este lado de los dados?',
	'ACP_DICE_SIDE_DELETE_SUCCESS'	=> 'Ha eliminado correctamente <strong>%s</strong> como un lado de dados.',
	'ACP_DICE_SIDES_AVAILABLE'		=> 'Lados disponibles',
	'ACP_DICE_SIDES_EXPLAIN'		=> 'Los lados permitidos que los usuarios pueden usar en sus notaciones de dados. Las skins son válidas cuando tienen imágenes para todos los lados que se proporcionadas aquí. Por ejemplo, lados: <small><samp>4, 5</samp></small>. Imágenes: <small><samp>d4_1 a d4_4, d5_1 a d5_5</samp></small>',
	'ACP_DICE_SIDES_NONE'			=> 'No hay lados de dados.',
	'ACP_DICE_SIDES_ONLY'			=> 'Solo lados disponibles',
	'ACP_DICE_SIDES_ONLY_DESC'		=> 'Si esta configuración está habilitada, los usuarios solo pueden usar los lados de los dados disponibles.',
	'ACP_DICE_SIDES_ONLY_STATS'		=> 'Los usuarios solo pueden usar los lados de los dados disponibles.',
	'ACP_DICE_SIDES_ONLY_UPTO'		=> 'Los usuarios pueden usar hasta %d lados de dados.',
	'ACP_DICE_SIDES_ONLY_UNLIMITED'	=> 'Los usuarios pueden usar lados de dados ilimitados.',

	'ACP_DICE_SKIN_INSTALL'				=> 'Instalar skin',
	'ACP_DICE_SKIN_INSTALL_CONFIRM'		=> '¿Está seguro de que desea instalar esta skin de dados?',
	'ACP_DICE_SKIN_INSTALL_SUCCESS'		=> 'Ha instalado correctamente <strong>%s</strong> como una skin de dados.',
	'ACP_DICE_SKIN_UNINSTALL'			=> 'Desinstalar skin',
	'ACP_DICE_SKIN_UNINSTALL_CONFIRM'	=> '¿Está seguro de que desea desinstalar esta skin de dados?',
	'ACP_DICE_SKIN_UNINSTALL_SUCCESS'	=> 'Ha desinstalado correctamente <strong>%s</strong> como una skin de dados.',
	'ACP_DICE_SKINS_AVAILABLE'			=> 'Skins disponibles',
	'ACP_DICE_SKINS_INSTALLED'			=> 'Skins instaladas',
	'ACP_DICE_SKINS_EXPLAIN'			=> 'Las skins se encuentran automáticamente cuando se sube al directorio designado. Las imágenes deben ser nombradas correctamente. Por ejemplo, para un dado de 4 lados: <small><samp>d4_1.gif, d4_2.gif, d4_3.gif, d4_4.gif</samp></small>',
	'ACP_DICE_SKINS_NONE'				=> 'No hay skins de dados.',

	'ACP_DICE_SUMMARY'				=> 'Resumen',

	'ACP_DICE_TOP_TOPICS'			=> 'Temas Top',
	'ACP_DICE_TOP_TOPICS_DESC'		=> 'Lista de temas con más tiradas.',
	'ACP_DICE_TOP_USERS'			=> 'Usuarios Top',
	'ACP_DICE_TOP_USERS_DESC'		=> 'Lista de usuarios con más tiradas.',

	'ACP_DICE_SKINS_DIR'				=> 'Directorio de imágenes de skins',
	'ACP_DICE_SKINS_DIR_DESC'			=> 'Esta ruta se utilizará para buscar skins. Cambiar esto reseteará las skins instaladas.<br><small>Ruta desde su directorio raíz de phpBB, por ejemplo: <samp>images/skins</samp></small>',
	'ACP_DICE_SKINS_PATH_ERROR'			=> 'El directorio "skins" que ha introducido no es válido.<br>El valor contiene los siguientes caracteres no admitidos: <br />%s',
	'ACP_DICE_SKINS_IMG_HEIGHT'			=> 'Altura de la imagen de skin',
	'ACP_DICE_SKINS_IMG_HEIGHT_DESC'	=> 'Altura de la imagen para las imágenes de skin de los dados. Debe tener entre 16 y 80 píxeles.',
	'ACP_DICE_SKINS_IMG_HEIGHT_ERROR'	=> 'La altura de la imagen que ingresó no es válida. El valor debe estar entre 16 y 80 píxeles.',
	'ACP_DICE_SKINS_IMG_WIDTH'			=> 'Anchura de la imagen de skin',
	'ACP_DICE_SKINS_IMG_WIDTH_DESC'		=> 'Anchura de la imagen para las imágenes de skin de los dados. Debe tener entre 16 y 80 píxeles.',
	'ACP_DICE_SKINS_IMG_WIDTH_ERROR'	=> 'La anchura de la imagen para las imágenes de skin de los dados. El valor debe tener entre 16 y 80 píxeles.',

	'ACP_DICE_ZERO_UNLIMITED'		=> 'Establezca el valor en <strong>0</strong> para una cantidad ilimitada.',

	// Settings
	'ACP_DICE_MAX_ROLLS'							=> 'Máximas tiradas por mensaje',
	'ACP_DICE_MAX_ROLLS_DESC'						=> 'El número máximo de tiradas que se pueden añadir por mensaje.',
	'ACP_DICE_PER_NOTATION'							=> 'Máximas dados por tirada',
	'ACP_DICE_PER_NOTATION_DESC'					=> 'La siguiente tirada tiene 2 dados: 5d6 <strong class="error">+</strong> 2d4',
	'ACP_DICE_QTY_PER_DICE'							=> 'Cantidad máxima de dados por tirada',
	'ACP_DICE_QTY_PER_DICE_DESC'					=> 'La siguiente tirada tiene una cantidad total de dados de 7: <strong class="error">5</strong>d6 + <strong class="error">2</strong>d4',
	'ACP_DICE_QTY_DICE_PER_NOTATION'				=> 'Cantidad máxima de dados por dado',
	'ACP_DICE_QTY_DICE_PER_NOTATION_DESC'			=> 'La siguiente tirada tiene 2 dados, ambos con una cantidad de dados de 3: <strong class="error">3</strong>d6 + <strong class="error">3</strong>d4',
	'ACP_DICE_SIDES_PER_DICE'						=> 'Lados máximos por dado',
	'ACP_DICE_SIDES_PER_DICE_DESC'					=> 'La siguiente tirada tiene 1 dado con 10 lados: 4d<strong class="error">10</strong>',
	'ACP_DICE_PC_DICE_PER_NOTATION'					=> 'Porcentaje máximo de dados por tirada',
	'ACP_DICE_PC_DICE_PER_NOTATION_DESC'			=> 'La siguiente tirada tiene 2 dados de porcentaje: 6d100 <strong class="error">+</strong> 3d%',
	'ACP_DICE_FUDGE_DICE_PER_NOTATION'				=> 'Dados de dulce de azúcar máximos por tirada',
	'ACP_DICE_FUDGE_DICE_PER_NOTATION_DESC'			=> 'La siguiente tirada tiene 1 dado de dulce de azúcar: 2d<strong class="error">F.2</strong> + 4d8',
	'ACP_DICE_EXPLODING_DICE_PER_NOTATION'			=> 'Dados de explosión máximos por tirada',
	'ACP_DICE_EXPLODING_DICE_PER_NOTATION_DESC'		=> 'La siguiente tirada tiene 1 dado de explosión: 2d6<strong class="error">!</strong> + 4d8',
	'ACP_DICE_PENETRATION_DICE_PER_NOTATION'		=> 'Dados penetrantes máximos por tirada',
	'ACP_DICE_PENETRATION_DICE_PER_NOTATION_DESC'	=> 'La siguiente tirada tiene 1 dado penetrante: 2d6<strong class="error">!p</strong> + 2d%',
	'ACP_DICE_COMPOUND_DICE_PER_NOTATION'			=> 'Dados de composición máximos por tirada',
	'ACP_DICE_COMPOUND_DICE_PER_NOTATION_DESC'		=> 'La siguiente tirada tiene 1 dado compuesto: 2d6<strong class="error">!!</strong> + 5d4',
]);
