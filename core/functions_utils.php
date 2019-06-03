<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\core;

/**
 * phpBB Studio's Dice Roll utility functions.
 */
class functions_utils
{
	/**
	 * Checks if the given value is a valid number.
	 *
	 * @param  mixed		$value		The value to check
	 * @return bool						Whether it is a valid number or not
	 * @access public
	 */
	public function is_numeric($value)
	{
		return (bool) (!is_array($value) && is_numeric($value) && is_finite(intval($value)));
	}

	/**
	 * Generates a random number between the min and the max (inclusive).
	 *
	 * @param  int			$min		The minimum number to be generated
	 * @param  int			$max		The maximum number to be generated
	 * @return int						The generated number
	 * @access public
	 */
	public function generate_number($min, $max)
	{
		$min = $min ? intval($min) : 1;
		$max = $max ? intval($max) : $min;

		if ($max <= $min)
		{
			return (int) $min;
		}

		return (int) floor((mt_rand() / mt_getrandmax()) * ($max - $min + 1) + $min);
	}

	/**
	 * Rolls a standard dice, callback method.
	 *
	 * @param  int			$sides		The amount of sides for the dice
	 * @return int						The outcome of the roll
	 * @access public
	 */
	public function default_dice($sides)
	{
		return (int) $this->generate_number(1, $sides);
	}

	/**
	 * Rolls a fudge dice, callback method.
	 *
	 * @param  mixed		$non_blanks
	 * @return int							The outcome of the roll
	 * @access public
	 */
	public function fudge_dice($non_blanks)
	{
		$non_blanks = (int) $non_blanks;
		$total = 0;

		if ($non_blanks === 2)
		{
			// default fudge (2 of each non-blank) = 1d3 - 2
			$total = $this->generate_number(1, 3) - 2;
		}
		else if ($non_blanks === 1)
		{
			// only 1 of each non-blank
			// on 1d6 a roll of 1 = -1, 6 = +1, others = 0
			$num = $this->generate_number(1, 6);

			if ($num === 1)
			{
				$total = -1;
			}
			else if ($num === 6)
			{
				$total = 1;
			}
		}

		return (int) $total;
	}

	/**
	 * Checks whether the value matches the given compare point
	 * and returns the corresponding success / failure state value
	 * success = 1, fail = 0
	 *
	 * @param  int			$value				The value to compare against
	 * @param  array		$compare_point		Array holding a value and an operator
	 * @return int								Success/failure state value
	 * @access public
	 */
	public function get_success_state_value($value, array $compare_point)
	{
		return $this->is_compare_point($compare_point, $value) ? 1 : 0;
	}

	/**
	 * Checks whether value matches the given compare point.
	 *
	 * @param  array		$compare_point		Array holding a value and an operator
	 * @param  int			$value				The value to compare against
	 * @return bool								Success/failure state
	 * @access public
	 */
	public function is_compare_point(array $compare_point, $value)
	{
		return (bool) (!empty($compare_point) ? $this->compare_numbers($value, $compare_point['value'], $compare_point['operator']) : false);
	}

	/**
	 * Takes two numbers and runs a mathematical equation on them, using the given operator.
	 *
	 * @param  int			$a					The first number: initial number
	 * @param  int			$b					The second number: number to be added, subtracted, multiplied by, divided by
	 * @param  string		$operator			The valid arithmetic operator (+, -, /, *) to use
	 * @return int								The outcome of the equation
	 * @access public
	 */
	public function equate_numbers($a, $b, $operator)
	{
		// Ensure values are numeric
		$a = $this->is_numeric($a) ? $a : 0;
		$b = $this->is_numeric($b) ? $b : 0;

		switch ($operator)
		{
			case '*':
				// Multiple the value
				$a *= $b;
			break;

			case '/':
				// Divide the value (handle division by zero)
				$a = !empty($b) ? $a / $b : 0;
			break;

			case '-':
				// subtract from value
				$a -= $b;
			break;

			default:
				// Add to the value
				$a += $b;
			break;
		}

		return $a;
	}

	/**
	 * Checks if `a` is comparative to `b` with the given operator.
	 *
	 * @param  int			$a					The first number
	 * @param  int			$b					The second number
	 * @param  string		$operator			The valid comparative operator (=, <, >, <=, >=, !=) to use
	 * @return bool								Outcome of the comparison
	 * @access public
	 */
	public function compare_numbers($a, $b, $operator)
	{

		switch ($operator)
		{
			case '=':
			case '==':
				$result = $a === $b;
			break;

			case '<':
				$result = $a < $b;
			break;

			case '>':
				$result = $a > $b;
			break;

			case '<=':
				$result = $a <= $b;
			break;

			case '>=':
				$result = $a >= $b;
			break;

			case '!':
			case '!=':
				$result = $a !== $b;
			break;

			default:
				$result = false;
			break;
		}

		return (bool) $result;
	}

	/**
	 * Takes an array of numbers and adds them together, returning the result.
	 *
	 * @param  array			$numbers		Arrays of numbers to be added to each other
	 * @return int								The outcome of the additions
	 * @access public
	 */
	public function sum_array($numbers)
	{
		return !is_array($numbers) ? 0 : array_reduce($numbers, function($prev, $current)
		{
			return $prev + ($this->is_numeric($current) ? intval($current) : 0);
		}, 0);
	}
}
