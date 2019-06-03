<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\exception;

/**
 * phpBB Studio's Dice OutOfBounds exception
 */
class out_of_bounds extends base
{
	/**
	 * Translate this exception
	 *
	 * @param  \phpbb\language\language		$lang		Language object
	 * @return string
	 * @access public
	 */
	public function get_message(\phpbb\language\language $lang)
	{
		return $this->translate_portions($lang, $this->message_full, 'EXCEPTION_OUT_OF_BOUNDS');
	}
}
