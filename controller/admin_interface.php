<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\controller;

/**
 * phpBB Studio's Dice Admin controller interface.
 */
interface admin_interface
{
	/**
	 * Display and handle any actions from the Dice ACP.
	 *
	 * @param  string	$action			Any action to handle
	 * @return void
	 * @access public
	 */
	public function handle($action);

	/**
	 * Make the custom form action available in the admin controller.
	 *
	 * @param  string	$u_action		Custom form action
	 * @return void
	 * @access public
	 */
	public function set_page_url($u_action);
}
