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
	'DICE_DICE'		=> 'Dados',

	'DICE_NOT_AJAX'					=> 'Las tiradas de dados se gestionan con solicitud AJAX. La solicitud actual no es AJAX y el servidor devolvió una respuesta no válida.',

	'DICE_SKIN'						=> 'Skin del dado',

	'DICE_ROLL'						=> 'Tirada de dados',
	'DICE_ROLL_ACTIONS'				=> 'Acciones',
	'DICE_ROLL_ADD_UNAUTH'			=> 'No está autorizado para añadir una tirada.',
	'DICE_ROLL_ADD_SUCCESS'			=> 'Ha añadido correctamente una tirada.',
	'DICE_ROLL_EDIT'				=> 'Editar tirada',
	'DICE_ROLL_EDIT_UNAUTH'			=> 'No está autorizado para editar esta tirada.',
	'DICE_ROLL_EDIT_CONFIRM'		=> 'Al editar una tirada desaparecerá completamente. ¡Esta acción no se puede deshacer!',
	'DICE_ROLL_EDIT_SUCCESS'		=> 'Ha editado correctamente esta tirada.',
	'DICE_ROLL_DELETE'				=> 'Borrar tirada',
	'DICE_ROLL_DELETE_UNAUTH'		=> 'No está autorizado para borrar esta tirada.',
	'DICE_ROLL_DELETE_CONFIRM'		=> '¿Está seguro de querer borrar esta tirada?',
	'DICE_ROLL_DELETE_SUCCESS'		=> 'Ha borrado correctamente esta tirada.',
	'DICE_ROLL_DICE'				=> 'Tirada de dados',
	'DICE_ROLL_FORUM_DISABLED'		=> 'La tiradas de dados se han deshabilitado para este foro',
	'DICE_ROLL_ID'					=> 'ID de tirada',
	'DICE_ROLL_LIMIT_REACHED'		=> 'Límite de dados alcanzado',
	'DICE_ROLL_NO_ROLL'				=> 'No se tiraron dados.',

	'DICE_ROLL_NOT_EXIST'			=> [
		1 => 'La tirada provista no existe.',
		2 => 'Las tiradas provistas no existen.',
	],
	'DICE_ROLL_NOTATION'			=> 'Notación de tiradas',
	'DICE_ROLL_NOTATION_CURRENT'	=> 'Notación actual de tirada',
	'DICE_ROLL_NOTATION_NEW'		=> 'Nueva notación de tirada',
	'DICE_ROLL_TIME'				=> 'Fecha de tirada',
	'DICE_ROLLS'					=> 'Tiradas de dados',
	'DICE_ROLLS_EXPLAIN'			=> 'Si desea añadir una o más tiradas, ingrese una notación de tirada y tire los dados. Puede colocarlo en línea en el cuadro de mensaje o editarlo/borrarlo a su voluntad más adelante.',
	'DICE_ROLLS_TOO_MANY'			=> 'Ya ha tirado demasiados dados para este mensaje.',
	'DICE_TEXT'						=> 'Texto',

	// Dice states
	'DICE_ROLL_COMPOUNDED'			=> 'Compuesto',
	'DICE_ROLL_EXPLODED'			=> 'Explosivo',
	'DICE_ROLL_HIGHEST'				=> 'Superior',
	'DICE_ROLL_LOWEST'				=> 'Inferior',
	'DICE_ROLL_PENETRATED'			=> 'Penetrado',
	'DICE_ROLL_SUCCESS'				=> 'Acierto',

	// Page -TRANSLATORS pay attention here, have fun! :-D
	'DICE_ROLL_PAGE_DICE_TESTER'		=> 'Prueba de dados',
	'DICE_ROLL_PAGE_RESULT'				=> 'Resultado',
	'DICE_ROLL_PAGE_LIMITATIONS'		=> 'Limitaciones',
	'DICE_ROLL_PAGE_UNLIMITED'			=> 'Ilimitado',
	'DICE_ROLL_PAGE_ROLLS_POST'			=> 'Tiradas por mensaje',
	'DICE_ROLL_PAGE_DICE_QTY'			=> 'Cantidad de dados',
	'DICE_ROLL_PAGE_SIDES_DICE'			=> 'Lados por dado',
	'DICE_ROLL_PAGE_ALLOWED_SIDES'		=> 'Lados permitidos',
	'DICE_ROLL_PAGE_ONLY_ALLOWED_SIDES'	=> 'Solo lados disponibles',
	'DICE_ROLL_PAGE_AVAIL_SIDES'		=> 'Lados disponibles',
	'DICE_ROLL_PAGE_FUDGE_DICE'			=> 'Dados de dulce de azúcar',
	'DICE_ROLL_PAGE_PERCENT_DICE'		=> 'Dados de porcentaje',
	'DICE_ROLL_PAGE_EXPLODING_DICE'		=> 'Dados explosivos',
	'DICE_ROLL_PAGE_PENETRATING_DICE'	=> 'Dados penetrantes',
	'DICE_ROLL_PAGE_COMPOUNDING_DICE'	=> 'Dados compuestos',
	'DICE_ROLL_PAGE_P_1_TITLE'			=> 'Explicación',
	'DICE_ROLL_PAGE_P_1'				=> 'Se aceptan los formatos de notación estándar, tales como <span class="dice-example">2d6+12</span>, y también el uso de <span class="dice-example">L</span> o <span class="dice-example">H</span> para representar la tirada más baja o más alta respectivamente.
		Por ejemplo: <span class="dice-example">4d6-L</span> (Una tirada de 4 dados de seis caras, arrojando el resultado más bajo).

		También puede usar operaciones para multiplicar y dividir, con sus operadores matemáticos; 1d6*5 o 2d10/d20.
		Sin embargo, el uso de los símbolos matemáticos <span class="dice-example">×</span> y <span class="dice-example">÷</span> no funcionan.',

	'DICE_ROLL_PAGE_LIST_1'				=> 'd6 o 1d',
	'DICE_ROLL_PAGE_LIST_1_2'			=> 'Un dado de 6 caras',
	'DICE_ROLL_PAGE_LIST_2'				=> '2d6',
	'DICE_ROLL_PAGE_LIST_2_2'			=> 'Dos dados de 6 caras',
	'DICE_ROLL_PAGE_LIST_3'				=> '1d6+4',
	'DICE_ROLL_PAGE_LIST_3_2'			=> 'Tire un dado de 6 caras y sume 4 al resultado',
	'DICE_ROLL_PAGE_LIST_4'				=> '2d10*4+1d20',
	'DICE_ROLL_PAGE_LIST_4_2'			=> 'Tire dos dados de 10 caras, multiplique por cuatro, y tire un dado de 20 caras',
	'DICE_ROLL_PAGE_LIST_5'				=> '2d10+4+2d20-L',
	'DICE_ROLL_PAGE_LIST_5_2'			=> 'Tire dos dados de 10 caras añade cuatro, y tire dos dados de 20 caras, quitando el más bajo de los dos',
	'DICE_ROLL_PAGE_LIST_6'				=> 'd%',
	'DICE_ROLL_PAGE_LIST_6_2'			=> 'Un dado de porcentaje - equivalente a d100',
	'DICE_ROLL_PAGE_LIST_7'				=> 'dF o dF.2',
	'DICE_ROLL_PAGE_LIST_7_2'			=> 'Un dado estándar de dulce de azúcar - 2 tercios de cada símbolo',
	'DICE_ROLL_PAGE_LIST_8'				=> 'dF.1',
	'DICE_ROLL_PAGE_LIST_8_2'			=> 'Un dado de dulce de azúcar no estándar - 1 positivo, 1 negativo, 4 en blanco',
	'DICE_ROLL_PAGE_LIST_9'				=> '2d6!',
	'DICE_ROLL_PAGE_LIST_9_2'			=> 'Dados explosivos - dos dados de 6 caras, que se vuelven a tirar por cada tirada del valor máximo',
	'DICE_ROLL_PAGE_LIST_10'			=> '2d6!!',
	'DICE_ROLL_PAGE_LIST_10_2'			=> 'Dados explosivos y compuestos - como explosivo, pero sumados en una sola tirada',
	'DICE_ROLL_PAGE_LIST_11'			=> '2d6!p',
	'DICE_ROLL_PAGE_LIST_11_2'			=> 'Dados penetrantes - como explosivo, pero restar 1 de cada tirada consecutiva',
	'DICE_ROLL_PAGE_LIST_12'			=> '2d6!!p',
	'DICE_ROLL_PAGE_LIST_12_2'			=> 'Dados penetrantes y compuestos - como explosivo y compuesto, pero restar 1 de cada tirada consecutiva',
	'DICE_ROLL_PAGE_LIST_13'			=> '2d6!>=4',
	'DICE_ROLL_PAGE_LIST_13_2'			=> 'Dados explosivos, pero solo si saca un 4 o mayor - También se puede usar con dados de composición y penetrantes',
	'DICE_ROLL_PAGE_LIST_14'			=> '2d6>4',
	'DICE_ROLL_PAGE_LIST_14_2'			=> 'Grupo de dados - cualquier cosa mayor que un 4 es un acierto. Cuenta el número de aciertos como el total',

	'DICE_ROLL_PAGE_P_2_TITLE'			=> 'Dado de porcentaje',
	'DICE_ROLL_PAGE_P_2'				=> 'Aunque los dados de porcentaje se puede tirar usando un <span class="dice-example">d100</span>, también puede usar d%, que hará lo mismo, devolviendo un número entre 0 y 100.',
	'DICE_ROLL_PAGE_P_3_TITLE'			=> 'Dados explosivos',
	'DICE_ROLL_PAGE_P_3'				=> 'Para dados explosivos, añada un signo de exclamación después de los lados del dado: <span class="dice-example">4d10!</span><br>
			Los dados explosivos lanzan un dado adicional si se lanza el máximo en ese dado.
			Si ese dado es también el máximo, se vuelve a tirar, y así sucesivamente, hasta que se haga una tirada que no sea el máximo.
			Por ejemplo: tirar un 6 en un d6, o un 10 en un d10.',
	'DICE_ROLL_PAGE_EXAMPLE_1_TITLE'	=> '2d6!: [4, 6!, 6!, 2] = 20',
	'DICE_ROLL_PAGE_EXAMPLE_1'			=> 'Cada dado explosivo se muestra como una tirada separada en la lista, como se muestra arriba.
					Donde explotó la segunda tirada, volvimos a tirar, y también explotó.
					La cuarta tirada, sin embargo no lo hizo, así que dejamos de tirar.',
	'DICE_ROLL_PAGE_EXAMPLE_2_TITLE'	=> '1d6!-L: [6!,6!,6!,3]-L = 18',
	'DICE_ROLL_PAGE_EXAMPLE_2'			=> 'Incluso puede usar <span class="dice-example">L</span> y <span class="dice-example">H</span>, que se verá en dados explosivos, así como tiradas normales.
				Aquí el dado explotó tres veces antes de no tirar un máximo. La última tirada se restó del total.',
	'DICE_ROLL_PAGE_P_4_TITLE'			=> 'Compuestos',
	'DICE_ROLL_PAGE_P_4'				=> 'A veces, es posible que desee que las tiradas de dados explosivos se añadan juntas en la misma tirada original.
			En esta situación, puede componer los dados utilizando dos signos de exclamación: <span class="dice-example">4d10!!</span>.
			Por ejemplo <em>(usando los ejemplos de dados explosivos de arriba)</em>',

	'DICE_ROLL_PAGE_EX_DETAILS_1'		=> '2d6!!: [4, 14!!] = 20',
	'DICE_ROLL_PAGE_EX_DETAILS_1_2'		=> 'las tiradas de dados explosivos de [6, 6, 2] se suman',
	'DICE_ROLL_PAGE_EX_DETAILS_2'		=> '1d6!!-L: [21!!]-L = 18',
	'DICE_ROLL_PAGE_EX_DETAILS_2_2'		=> 'las tiradas de dados explosivos de [6, 6, 6, 3] se suman',

	'DICE_ROLL_PAGE_P_5_TITLE'			=> 'Penetrante',
	'DICE_ROLL_PAGE_P_5'				=> 'Algunos sistemas de dados explosivos usan una regla penetrante. Tomado de las reglas <a href="https://www.kenzerco.com/free_files/hackmaster_basic_free_.pdf#page=51" target="_blank">Hackmaster Basic</a>',
	'DICE_ROLL_PAGE_P_6'				=> 'Si tira el valor máximo en este dado en particular, puede volver a tirar y agregar el resultado del dado adicional, menos un punto, al total (la penetración puede dar como resultado simplemente el valor máximo del dado si posteriormente se lanza un 1, ya que cualquier tonto sabe que 1-1 = 0).
			Este proceso continúa indefinidamente siempre que el dado en cuestión continúe llegando al máximo (pero siempre hay solo un -1 extraído del dado adicional, incluso si es, por ejemplo, el tercer dado de penetración).',
	'DICE_ROLL_PAGE_P_7'				=> 'Entonces, si tiró <span class="dice-example">1d6</span> (penetrante) y obtuvo un 6, tiraría otro <span class="dice-example">d6</span>, restando 1 del resultado.
			Si ese <span class="dice-example">d6</span> sacara un 6 (antes del -1) penetraría, y así sucesivamente.
			La sintaxis para penetrar es muy similar a explosivo, pero con una <strong>p</strong> minúscula anexada, como <span class="dice-example">2d6!p</span>.
			Por ejemplo <em>(Usando el mismo ejemplo de dados explosivos de arriba)</em>',
	'DICE_ROLL_PAGE_EXAMPLE_3'			=> '2d6!p: [4, 6!p, 5, 1] = 20',
	'DICE_ROLL_PAGE_P_8'				=> 'Donde explotó la segunda tirada, tiramos de nuevo, lo que también explotó (tiró un 6). La cuarta tirada sin embargo, sacó un 2, así que no penetró, y dejamos de tirar.
			Remember that we subtract 1 from penetrated rolls, which is why we show 5 and 1, instead of 6, and 2.
			<br>
			También puede componer dados penetrantes, así: <span class="dice-example">2d6!!p</span>',
	'DICE_ROLL_PAGE_P_9_TITLE'			=> 'Punto de comparación',
	'DICE_ROLL_PAGE_P_9'				=> 'Por defecto, los dados explosivos y penetrantes lo hacen si saca el mayor número posible en los dados (por ejemplo, un 6 en un <span class="dice-example">d6</span>, un 1 en un dado de dulce de azúcar).
			Puede cambiar fácilmente el punto de comparación de explosión añadiendo una comparación después de él.',
	'DICE_ROLL_PAGE_EXAMPLE_4_TITLE'			=> 'Para explosivo solo si saca un 4',
	'DICE_ROLL_PAGE_EXAMPLE_4'			=> '2d6!=4',
	'DICE_ROLL_PAGE_EXAMPLE_5_TITLE'	=> 'O explosivo si hace tirada de algo sobre un 4',
	'DICE_ROLL_PAGE_EXAMPLE_5'			=> '2d6!>4',
	'DICE_ROLL_PAGE_P_10'				=> 'También puede usar esto con dados penetrantes y compuestos',
	'DICE_ROLL_PAGE_EXAMPLE_6_TITLE'	=> 'compuesto si saca un 4 o menos',
	'DICE_ROLL_PAGE_EXAMPLE_6'			=> '2d6!!<=4',
	'DICE_ROLL_PAGE_EXAMPLE_7_TITLE'	=> 'penetra si no saca un 4',
	'DICE_ROLL_PAGE_EXAMPLE_7'			=> '2d6!p!=4',
	'DICE_ROLL_PAGE_P_11_TITLE'			=> 'Dados de dulce de azúcar',
	'DICE_ROLL_PAGE_P_11'				=> 'La notación de dulce de azúcar también es compatible. Permite tanto <span class="dice-example">dF.2</span> y menos común <span class="dice-example">dF.1</span>.<br>
			También puede utilizarlo junto con otros operadores y adiciones. Ejemplos',
	'DICE_ROLL_PAGE_EXAMPLE_8_TITLE'	=> 'dF',
	'DICE_ROLL_PAGE_EXAMPLE_8'			=> 'esto es lo mismo que',
	'DICE_ROLL_PAGE_EXAMPLE_8_BIS'		=> 'dF.2',
	'DICE_ROLL_PAGE_EXAMPLE_9_TITLE'	=> '4dF.2',
	'DICE_ROLL_PAGE_EXAMPLE_9'			=> 'tirar 4 dados de dulce de azúcar estándar',
	'DICE_ROLL_PAGE_EXAMPLE_10_TITLE'	=> '4dF.2-L',
	'DICE_ROLL_PAGE_EXAMPLE_10'			=> 'tirar 4 dados de dulce de azúcar estándar, restando el resultado más bajo',
	'DICE_ROLL_PAGE_EXAMPLE_11_TITLE'	=> 'dF.1*2',
	'DICE_ROLL_PAGE_EXAMPLE_11'			=> 'tirar los dados de dulce de azúcar no estándar, multiplicando el resultado por 2',
	'DICE_ROLL_PAGE_P_12_TITLE'			=> 'Piscinas de dados',
	'DICE_ROLL_PAGE_P_12'				=> 'Algunos sistemas usan piscinas de dados, por lo que el total es igual a la cantidad de dados lanzados que cumplen con una condición fija, en lugar del valor total de las tiradas.
			Por ejemplo, una <strong>piscina</strong> de dados de 10 lados donde se cuenta el número de dados que tiran un 8 o más como <strong>acierto</strong>.
			Esto se puede lograr con: <span class="dice-example">5d10>=8</span>.<br>
			Puede definir varias condiciones de acierto, simplemente agregando comparaciones de números directamente después de la tirada de dados.<br>
			Debido a esto, <strong>no</strong> puede tener un dado de grupo que también sea explosivo. Ejemplos',
	'DICE_ROLL_PAGE_P_13'				=> 'Puede mezclar dados de grupo con otros tipos de dados o ecuaciones, y utilizará el número de aciertos como el valor en la ecuación',

	'DICE_ROLL_PAGE_EX_DETAILS_3'		=> '2d6=6: [4,6*] = 1',
	'DICE_ROLL_PAGE_EX_DETAILS_3_2'		=> 'solo un resultado de 6 es un acierto',
	'DICE_ROLL_PAGE_EX_DETAILS_4'		=> '4d3>1: [1,3*,2*,1] = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_4_2'		=> 'superior que un 1 es un acierto',
	'DICE_ROLL_PAGE_EX_DETAILS_5'		=> '4d3<2: [1*,3,2,1*] = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_5_2'		=> 'inferior que un 2 es un acierto',
	'DICE_ROLL_PAGE_EX_DETAILS_6'		=> '5d8>=5: [2,4,6*,3,8*] = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_6_2'		=> 'mayor o igual a 5 es un acierto',
	'DICE_ROLL_PAGE_EX_DETAILS_7'		=> '6d10<=4: [7,2*,10,3*,3*,4*] = 4',
	'DICE_ROLL_PAGE_EX_DETAILS_7_2'		=> 'menor o igual a 4 es un acierto',

	'DICE_ROLL_PAGE_EX_DETAILS_8'		=> '2d6>4+3d5: [4,5*]+[3,1,1] = 6',
	'DICE_ROLL_PAGE_EX_DETAILS_8_2'		=> '1 acierto + los valores en bruto de las otras tiradas',
	'DICE_ROLL_PAGE_EX_DETAILS_9'		=> '2d6>4*d6!: [6*,5*]*[6!,4] = 20',
	'DICE_ROLL_PAGE_EX_DETAILS_9_2'		=> '1 acierto * los valores en bruto de las otras tiradas',
	'DICE_ROLL_PAGE_EX_DETAILS_10'		=> '2d6>4+2: [3,5*]+2 = 3',
	'DICE_ROLL_PAGE_EX_DETAILS_10_2'	=> '1 acierto + 2',
	'DICE_ROLL_PAGE_EX_DETAILS_11'		=> '2d6>4+H: [3,5*]+H = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_11_2'	=> 'La tirada más alta es 5, lo que es un acierto, valor de 1',
	'DICE_ROLL_PAGE_EX_DETAILS_12'		=> '2d6<4+H: [3*,5]+H = 1',
	'DICE_ROLL_PAGE_EX_DETAILS_12_2'	=> 'La tirada más alta es 5, lo que es un fallo, valor de 0',
]);
