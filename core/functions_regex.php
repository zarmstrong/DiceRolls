<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\core;

/**
 * phpBB Studio's Dice Regex functions.
 */
class functions_regex
{
	/**
	 * Matches a basic arithmetic operator.
	 *
	 * @param  string
	 * @access protected
	 */
	protected $arithmetic_operator = '[+\\-*\\/]';

	/**
	 * Matches a basic comparison operator.
	 *
	 * @param  string
	 * @access protected
	 */
	protected $comparison_operators = '[<>!]?={1,3}|[<>]';

	/**
	 * Matches exploding/penetrating dice notation.
	 *
	 * @param  string
	 * @access protected
	 */
	protected $explode = '(!{1,2}p?)';

	/**
	 * Matches a number comparison (ie. <=4, =5, >3, !=1).
	 *
	 * @return string
	 * @access protected
	 */
	protected function number_comparison()
	{
		return '(' . $this->comparison_operators . ')([0-9]+)';
	}

	/**
	 * Matches the numbers for a 'fudge' die (ie. F, F.2).
	 *
	 * @return  string
	 * @access protected
	 */
	protected function fudge()
	{
		return 'F(?:\\.([12]))?';
	}

	/**
	 * Matches a dice (ie. 2d6, d10, d%, dF, dF.2).
	 *
	 * @return string
	 * @access protected
	 */
	protected function dice()
	{
		return '([1-9][0-9]*)?d([1-9][0-9]*|%|' . $this->fudge() . ')';
	}

	/**
	 * Matches a dice, optional exploding/penetrating notation and roll comparison.
	 *
	 * @return string
	 * @access protected
	 */
	protected function dice_full()
	{
		return $this->dice() . $this->explode . '?(?:' . $this->number_comparison() . ')?';
	}

	/**
	 * Matches the addition to a dice (ie. +4, -10, *2, -L).
	 *
	 * @return string
	 * @access protected
	 */
	protected function addition()
	{
		return '(' . $this->arithmetic_operator . ')([1-9]+0?(?![0-9]*d)|H|L)';
	}

	/**
	 * Matches a standard dice notation. i.e;
	 * 3d10-2
	 * 4d20-L
	 * 2d7/4
	 * 3d8*2
	 * 2d3+4-1
	 * 2d10-H*1d6/2
	 *
	 * @return string
	 * @access protected
	 */
	protected function notation()
	{
		return '('. $this->arithmetic_operator . ')?' . $this->dice_full() . '((?:' . $this->addition() . ')*)';
	}

	/**
	 * Get a regex from this class.
	 *
	 * @param  string		$name				The regex name from this class
	 * @param  bool			$match_whole		If it should match the whole string
	 * @return string							A regular expression
	 * @access public
	 * @throws \phpbbstudio\dice\exception\unexpected_value
	 */
	public function get($name, $match_whole = false)
	{
		if (empty($name))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_REGEX_NAME', 'FIELD_MISSING']);
		}
		else if ((gettype($name) !== 'string') || !method_exists($this, $name))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_REGEX_NAME', 'ROLL_NAME_NOT_FOUND']);
		}

		$pattern = $this->{$name}();

		return '/' . ($match_whole ? '^' : '') . $pattern . ($match_whole ? '$' : '') . '/';
	}
}
