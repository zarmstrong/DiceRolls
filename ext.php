<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice;

class ext extends \phpbb\extension\base
{
	/**
	 * Check whether the extension can be enabled.
	 * Provides meaningful(s) error message(s) and the back-link on failure.
	 * CLI compatible
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$streams = stream_get_wrappers();

		if (phpbb_version_compare(PHPBB_VERSION, '3.2.5', '>=')
			&& phpbb_version_compare(PHPBB_VERSION, '4.0.0@dev', '<')
			&& phpbb_version_compare(PHP_VERSION, '5.5', '>=')
			&& in_array('glob', $streams)
		)
		{
			return true;
		}
	}
}
