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
	'DICE_DICE'		=> 'Dice',

	'DICE_NOT_AJAX'					=> 'Dice rolls are managed with AJAX request. The current request is not AJAX and the server returned an invalid reply.',

	'DICE_SKIN'						=> 'Dice skin',

	'DICE_ROLL'						=> 'Dice roll',
	'DICE_ROLL_ACTIONS'				=> 'Actions',
	'DICE_ROLL_ADD_UNAUTH'			=> 'You are not authorised to add a roll.',
	'DICE_ROLL_ADD_SUCCESS'			=> 'You have successfully added a roll.',
	'DICE_ROLL_EDIT'				=> 'Edit roll',
	'DICE_ROLL_EDIT_UNAUTH'			=> 'You are not authorised to edit this roll.',
	'DICE_ROLL_EDIT_CONFIRM'		=> 'Editing a roll will completely re-roll it. This action can not be undone!',
	'DICE_ROLL_EDIT_SUCCESS'		=> 'You have successfully edited this roll.',
	'DICE_ROLL_DELETE'				=> 'Delete roll',
	'DICE_ROLL_DELETE_UNAUTH'		=> 'You are not authorised to delete this roll.',
	'DICE_ROLL_DELETE_CONFIRM'		=> 'Are you sure you wish to delete this roll?',
	'DICE_ROLL_DELETE_SUCCESS'		=> 'You have successfully deleted this roll.',
	'DICE_ROLL_DICE'				=> 'Roll the dice',
	'DICE_ROLL_FORUM_DISABLED'		=> 'Dice rolls have been disabled for this forum',
	'DICE_ROLL_ID'					=> 'Roll id',
	'DICE_ROLL_LIMIT_REACHED'		=> 'Dice limit reached',
	'DICE_ROLL_NO_ROLL'				=> 'No dice were rolled.',

	'DICE_ROLL_NOT_EXIST'			=> [
		1 => 'The provided roll does not exist.',
		2 => 'The provided rolls do no exist.',
	],
	'DICE_ROLL_NOTATION'			=> 'Roll notation',
	'DICE_ROLL_NOTATION_CURRENT'	=> 'Current roll notation',
	'DICE_ROLL_NOTATION_NEW'		=> 'New roll notation',
	'DICE_ROLL_TIME'				=> 'Roll time',
	'DICE_ROLLS'					=> 'Dice rolls',
	'DICE_ROLLS_EXPLAIN'			=> 'If you wish to add one or more rolls enter a roll notation and roll the dice. You may place it inline in the message box or edit/delete it at your will later on.',
	'DICE_ROLLS_TOO_MANY'			=> 'You have already rolled too many dice for this post.',
	'DICE_TEXT'						=> 'Text',

	// Dice states
	'DICE_ROLL_COMPOUNDED'			=> 'Compounded',
	'DICE_ROLL_EXPLODED'			=> 'Exploded',
	'DICE_ROLL_HIGHEST'				=> 'Highest',
	'DICE_ROLL_LOWEST'				=> 'Lowest',
	'DICE_ROLL_PENETRATED'			=> 'Penetrated',
	'DICE_ROLL_SUCCESS'				=> 'Success',

	// Page -TRANSLATORS pay attention here, have fun! :-D
	'DICE_ROLL_PAGE_DICE_TESTER'		=> 'Dice tester',
	'DICE_ROLL_PAGE_RESULT'				=> 'Result',
	'DICE_ROLL_PAGE_LIMITATIONS'		=> 'Limitations',
	'DICE_ROLL_PAGE_UNLIMITED'			=> 'Unlimited',
	'DICE_ROLL_PAGE_ROLLS_POST'			=> 'Rolls per post',
	'DICE_ROLL_PAGE_DICE_QTY'			=> 'Dice quantity',
	'DICE_ROLL_PAGE_SIDES_DICE'			=> 'Sides per dice',
	'DICE_ROLL_PAGE_ALLOWED_SIDES'		=> 'Allowed sides',
	'DICE_ROLL_PAGE_ONLY_ALLOWED_SIDES'	=> 'Only available sides',
	'DICE_ROLL_PAGE_AVAIL_SIDES'		=> 'Available sides',
	'DICE_ROLL_PAGE_FUDGE_DICE'			=> 'Fudge dice',
	'DICE_ROLL_PAGE_PERCENT_DICE'		=> 'Percentage dice',
	'DICE_ROLL_PAGE_EXPLODING_DICE'		=> 'Exploding dice',
	'DICE_ROLL_PAGE_PENETRATING_DICE'	=> 'Penetrating dice',
	'DICE_ROLL_PAGE_COMPOUNDING_DICE'	=> 'Compounding dice',
	'DICE_ROLL_PAGE_P_1_TITLE'			=> 'Explanation',
	'DICE_ROLL_PAGE_P_1'				=> 'The standard notation formats are accepted, such as <span class="dice-example">2d6+12</span>, and also the use of <span class="dice-example">L</span> or <span class="dice-example">H</span> to represent the lowest or highest roll respectively.
		For example: <span class="dice-example">4d6-L</span> (A roll of 4 six-sided dice, dropping the lowest result).

		You can also use multiply and divide mathematical operators; 1d6*5 or 2d10/d20.
		However, the use of the mathematical symbols <span class="dice-example">×</span> and <span class="dice-example">÷</span> do not work.',

	'DICE_ROLL_PAGE_LIST_1'				=> 'd6 or 1d',
	'DICE_ROLL_PAGE_LIST_1_2'			=> 'A 6 sided die',
	'DICE_ROLL_PAGE_LIST_2'				=> '2d6',
	'DICE_ROLL_PAGE_LIST_2_2'			=> 'Two 6 sided dice',
	'DICE_ROLL_PAGE_LIST_3'				=> '1d6+4',
	'DICE_ROLL_PAGE_LIST_3_2'			=> 'Roll a 6 sided dice and add 4 to the result',
	'DICE_ROLL_PAGE_LIST_4'				=> '2d10*4+1d20',
	'DICE_ROLL_PAGE_LIST_4_2'			=> 'Roll two 10 sided dice multiply by four, and roll one 20 sided die',
	'DICE_ROLL_PAGE_LIST_5'				=> '2d10+4+2d20-L',
	'DICE_ROLL_PAGE_LIST_5_2'			=> 'Roll two 10 sided dice add four, and roll two 20 sided die, taking away the lowest of the two',
	'DICE_ROLL_PAGE_LIST_6'				=> 'd%',
	'DICE_ROLL_PAGE_LIST_6_2'			=> 'A percentile die - equivalent to d100',
	'DICE_ROLL_PAGE_LIST_7'				=> 'dF or dF.2',
	'DICE_ROLL_PAGE_LIST_7_2'			=> 'A standard fudge dice - 2 thirds of each symbol',
	'DICE_ROLL_PAGE_LIST_8'				=> 'dF.1',
	'DICE_ROLL_PAGE_LIST_8_2'			=> 'A non-standard fudge dice - 1 positive, 1 negative, 4 blank',
	'DICE_ROLL_PAGE_LIST_9'				=> '2d6!',
	'DICE_ROLL_PAGE_LIST_9_2'			=> 'Exploding dice - two 6 sided die, rolling again for each roll of the maximum value',
	'DICE_ROLL_PAGE_LIST_10'			=> '2d6!!',
	'DICE_ROLL_PAGE_LIST_10_2'			=> 'Exploding & compounding dice - like exploding, but adding together into single roll',
	'DICE_ROLL_PAGE_LIST_11'			=> '2d6!p',
	'DICE_ROLL_PAGE_LIST_11_2'			=> 'Penetrating dice - like exploding, but subtract 1 from each consecutive roll',
	'DICE_ROLL_PAGE_LIST_12'			=> '2d6!!p',
	'DICE_ROLL_PAGE_LIST_12_2'			=> 'Penetrating & compounding dice - like exploding & compounding, but subtract 1 from each consecutive roll',
	'DICE_ROLL_PAGE_LIST_13'			=> '2d6!>=4',
	'DICE_ROLL_PAGE_LIST_13_2'			=> 'Exploding dice, but only if you roll a 4 or greater - Also usable with compounding and penetrating dice',
	'DICE_ROLL_PAGE_LIST_14'			=> '2d6>4',
	'DICE_ROLL_PAGE_LIST_14_2'			=> 'Dice pool - anything greater than a 4 is a success. Counts the number of successes as the total',

	'DICE_ROLL_PAGE_P_2_TITLE'			=> 'Percentile dice',
	'DICE_ROLL_PAGE_P_2'				=> 'Although percentile dice can be rolled by using a <span class="dice-example">d100</span>, you can also use d%, which will do the same thing, returning a number between 0 and 100.',
	'DICE_ROLL_PAGE_P_3_TITLE'			=> 'Exploding dice',
	'DICE_ROLL_PAGE_P_3'				=> 'To explode a dice, add an exclamation mark after the die sides: <span class="dice-example">4d10!</span><br>
			Exploding dice roll an additional die if the maximum, on that die, is rolled.
			If that die is also the maximum it is rolled again, and so forth, until a roll is made that is not the maximum.
			For example: rolling a 6 on a d6, or a 10 on a d10.',
	'DICE_ROLL_PAGE_EXAMPLE_1_TITLE'	=> '2d6!: [4, 6!, 6!, 2] = 20',
	'DICE_ROLL_PAGE_EXAMPLE_1'			=> 'Each exploded die shows as a separate roll in the list, like shown above.
					Where the second roll exploded, so we rolled again, which also exploded.
					The fourth role, however, did not, so we stop rolling.',
	'DICE_ROLL_PAGE_EXAMPLE_2_TITLE'	=> '1d6!-L: [6!,6!,6!,3]-L = 18',
	'DICE_ROLL_PAGE_EXAMPLE_2'			=> 'You can even use <span class="dice-example">L</span> and <span class="dice-example">H</span>, which will look at exploded dice, as well as normal rolls.
				Here the die exploded three times before not rolling a maximum. The last roll was subtracted from the total.',
	'DICE_ROLL_PAGE_P_4_TITLE'			=> 'Compounding',
	'DICE_ROLL_PAGE_P_4'				=> 'Sometimes, you may want the exploded dice rolls to be added together under the same, original roll.
			In this situation, you can compound the dice by using two exclamation marks: <span class="dice-example">4d10!!</span>.
			For example <em>(using the examples of exploding dice above)</em>',

	'DICE_ROLL_PAGE_EX_DETAILS_1'		=> '2d6!!: [4, 14!!] = 20',
	'DICE_ROLL_PAGE_EX_DETAILS_1_2'		=> 'the exploded dice rolls of [6, 6, 2] are added together',
	'DICE_ROLL_PAGE_EX_DETAILS_2'		=> '1d6!!-L: [21!!]-L = 18',
	'DICE_ROLL_PAGE_EX_DETAILS_2_2'		=> 'the exploded dice rolls of [6, 6, 6, 3] are added together',

	'DICE_ROLL_PAGE_P_5_TITLE'			=> 'Penetrating',
	'DICE_ROLL_PAGE_P_5'				=> 'Some exploding dice system use a penetrating rule. Taken from the <a href="https://www.kenzerco.com/free_files/hackmaster_basic_free_.pdf#page=51" target="_blank">Hackmaster Basic</a> rules',
	'DICE_ROLL_PAGE_P_6'				=> 'Should you roll the maximum value on this particular die, you may re-roll and add the result of the extra die, less one point, to the total (penetration can actually result in simply the maximum die value if a 1 is subsequently rolled, since any fool knows that 1-1=0).
			This process continues indefinitely as long as the die in question continues to come up maximum (but there’s always only a –1 subtracted from the extra die, even if it’s, say, the third die of penetration).',
	'DICE_ROLL_PAGE_P_7'				=> 'So, if you rolled <span class="dice-example">1d6</span> (penetrating), and got a 6, you would roll another <span class="dice-example">d6</span>, subtracting 1 from the result.
			If that <span class="dice-example">d6</span> rolled a 6 (before the -1) it would penetrate, and so on.
			The syntax for penetrating is very similar to exploding, but with a lowercase <strong>p</strong> appended, like <span class="dice-example">2d6!p</span>.
			For example <em>(Using the same example from exploding dice above)</em>',
	'DICE_ROLL_PAGE_EXAMPLE_3'			=> '2d6!p: [4, 6!p, 5, 1] = 20',
	'DICE_ROLL_PAGE_P_8'				=> 'Where the second roll exploded, so we rolled again, which also exploded (rolled a 6). The fourth role, however, rolled a 2, so did not penetrate, so we stop rolling.
			Remember that we subtract 1 from penetrated rolls, which is why we show 5 and 1, instead of 6, and 2.
			<br>
			You can also compound penetrating dice, like so: <span class="dice-example">2d6!!p</span>',
	'DICE_ROLL_PAGE_P_9_TITLE'			=> 'Compare point',
	'DICE_ROLL_PAGE_P_9'				=> 'By default, exploding and penetrating dice do so if you roll the highest number possible on the dice (ie. a 6 on a <span class="dice-example">d6</span>, a 1 on a Fudge die).
			You can easily change the exploding compare point by adding a comparison after it.',
	'DICE_ROLL_PAGE_EXAMPLE_4_TITLE'			=> 'To explode only if you roll a 4',
	'DICE_ROLL_PAGE_EXAMPLE_4'			=> '2d6!=4',
	'DICE_ROLL_PAGE_EXAMPLE_5_TITLE'	=> 'Or exploding if you roll anything over a 4',
	'DICE_ROLL_PAGE_EXAMPLE_5'			=> '2d6!>4',
	'DICE_ROLL_PAGE_P_10'				=> 'You can also use this with penetrating and compounding dice',
	'DICE_ROLL_PAGE_EXAMPLE_6_TITLE'	=> 'compound if you roll a 4 or lower',
	'DICE_ROLL_PAGE_EXAMPLE_6'			=> '2d6!!<=4',
	'DICE_ROLL_PAGE_EXAMPLE_7_TITLE'	=> 'penetrate if you do not roll a 4',
	'DICE_ROLL_PAGE_EXAMPLE_7'			=> '2d6!p!=4',
	'DICE_ROLL_PAGE_P_11_TITLE'			=> 'Fudge dice',
	'DICE_ROLL_PAGE_P_11'				=> 'Fudge notation is also supported. It allows both <span class="dice-example">dF.2</span> and less common <span class="dice-example">dF.1</span>.<br>
			You can also use it in conjunction with other operators and additions. Examples',
	'DICE_ROLL_PAGE_EXAMPLE_8_TITLE'	=> 'dF',
	'DICE_ROLL_PAGE_EXAMPLE_8'			=> 'this is the same as',
	'DICE_ROLL_PAGE_EXAMPLE_8_BIS'		=> 'dF.2',
	'DICE_ROLL_PAGE_EXAMPLE_9_TITLE'	=> '4dF.2',
	'DICE_ROLL_PAGE_EXAMPLE_9'			=> 'roll 4 standard fudge dice',
	'DICE_ROLL_PAGE_EXAMPLE_10_TITLE'	=> '4dF.2-L',
	'DICE_ROLL_PAGE_EXAMPLE_10'			=> 'roll 4 standard fudge dice, subtracting the lowest result',
	'DICE_ROLL_PAGE_EXAMPLE_11_TITLE'	=> 'dF.1*2',
	'DICE_ROLL_PAGE_EXAMPLE_11'			=> 'roll non-standard fudge dice, multiplying the result by 2',
	'DICE_ROLL_PAGE_P_12_TITLE'			=> 'Dice pools',
	'DICE_ROLL_PAGE_P_12'				=> 'Some systems use dice pool, whereby the total is equal to the number of dice rolled that meet a fixed condition, rather than the total value of the rolls.
			For example, a <strong>pool</strong> of 10 sided dice where you count the number of dice that roll an 8 or higher as <strong>successes</strong>.
			This can be achieved with: <span class="dice-example">5d10>=8</span>.<br>
			You can define various success conditions, by simply adding number comparisons directly after the dice roll.<br>
			Because of this, you can <strong>not</strong> have a pool dice that also explodes. Examples',
	'DICE_ROLL_PAGE_P_13'				=> 'You can mix pool dice with other dice types or equations, and it will use the number of successes as the value in the equation',

	'DICE_ROLL_PAGE_EX_DETAILS_3'		=> '2d6=6: [4,6*] = 1',
	'DICE_ROLL_PAGE_EX_DETAILS_3_2'		=> 'only a roll of 6 is a success',
	'DICE_ROLL_PAGE_EX_DETAILS_4'		=> '4d3>1: [1,3*,2*,1] = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_4_2'		=> 'higher than a 1 is a success',
	'DICE_ROLL_PAGE_EX_DETAILS_5'		=> '4d3<2: [1*,3,2,1*] = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_5_2'		=> 'lower than a 2 is a success',
	'DICE_ROLL_PAGE_EX_DETAILS_6'		=> '5d8>=5: [2,4,6*,3,8*] = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_6_2'		=> 'higher than or equal to 5 is a success',
	'DICE_ROLL_PAGE_EX_DETAILS_7'		=> '6d10<=4: [7,2*,10,3*,3*,4*] = 4',
	'DICE_ROLL_PAGE_EX_DETAILS_7_2'		=> 'less than or equal to 4 is a success',

	'DICE_ROLL_PAGE_EX_DETAILS_8'		=> '2d6>4+3d5: [4,5*]+[3,1,1] = 6',
	'DICE_ROLL_PAGE_EX_DETAILS_8_2'		=> '1 success + the raw values of the other rolls',
	'DICE_ROLL_PAGE_EX_DETAILS_9'		=> '2d6>4*d6!: [6*,5*]*[6!,4] = 20',
	'DICE_ROLL_PAGE_EX_DETAILS_9_2'		=> '1 success * raw values of the other rolls',
	'DICE_ROLL_PAGE_EX_DETAILS_10'		=> '2d6>4+2: [3,5*]+2 = 3',
	'DICE_ROLL_PAGE_EX_DETAILS_10_2'	=> '1 success + 2',
	'DICE_ROLL_PAGE_EX_DETAILS_11'		=> '2d6>4+H: [3,5*]+H = 2',
	'DICE_ROLL_PAGE_EX_DETAILS_11_2'	=> 'Highest roll is 5, which is a success, so value of 1',
	'DICE_ROLL_PAGE_EX_DETAILS_12'		=> '2d6<4+H: [3*,5]+H = 1',
	'DICE_ROLL_PAGE_EX_DETAILS_12_2'	=> 'Highest roll is 5, which is a failure, so value of 0',
]);
