<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\acp;

/**
 * phpBB Studio's Dice ACP module.
 */
class dice_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * @param $id
	 * @param $mode
	 * @throws \Exception
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		// Get services
		$controller	= $phpbb_container->get('phpbbstudio.dice.controller.admin');
		$language	= $phpbb_container->get('language');
		$request	= $phpbb_container->get('request');

		// Add our lang file
		$language->add_lang('acp_dice', 'phpbbstudio/dice');

		// Request any action
		$action = $request->variable('action', '', true);

		switch ($action)
		{
			case 'example':
				// Set template filename
				$this->tpl_name = 'dice_example';

				// Set page title
				$this->page_title = 'ACP_DICE_EXAMPLE';
			break;

			case 'locations':
				// Set template filename
				$this->tpl_name = 'dice_locations';

				// Set page title
				$this->page_title = 'ACP_DICE_LOCATIONS';
			break;

			default:
				// Set template filename
				$this->tpl_name = 'dice_acp';

				// Set page title
				$this->page_title = 'ACP_DICE_DASH';
			break;
		}

		// Make the $u_action variable available in the admin controller
		$controller->set_page_url($this->u_action);

		// Send it off to be handled with
		$controller->handle($action);
	}
}
