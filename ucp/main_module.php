<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\ucp;

/**
 *  phpBB Studio's Dice UCP module.
 */
class main_module
{
	var $tpl_name;
	var $page_title;
	var $u_action;

	/**
	 * @param $id
	 * @param $mode
	 * @throws \Exception
	 */
	function main($id, $mode)
	{
		/** @var \phpbb\request\request $request */
		global $db, $request, $template, $user, $phpbb_container;

		/** @var \phpbbstudio\dice\core\functions_common $functions */
		$functions = $phpbb_container->get('phpbbstudio.dice.functions.common');

		$this->tpl_name = 'ucp_dice_body';
		$this->page_title = $user->lang('UCP_DICE_TITLE');

		$form_key = 'ucp_dice_body';
		add_form_key($form_key);

		$data = [
			'dice_u_skin' => $request->variable('dice_u_skin', $user->data['dice_u_skin']),
		];

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key($form_key))
			{
				trigger_error($user->lang('FORM_INVALID'), E_USER_WARNING);
			}

			$sql = 'UPDATE ' . $phpbb_container->getParameter('tables.users') . '
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE user_id = ' . (int) $user->data['user_id'];
			$db->sql_query($sql);

			meta_refresh(3, $this->u_action);
			$message = $user->lang('UCP_DICE_SAVED') . '<br /><br />' . $user->lang('RETURN_UCP', '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);
		}

		$skins = $functions->get_dice_skins(true);

		$template->assign_vars([
			'USER_SKIN'		=> $functions->build_dice_select($skins, $user->data['dice_u_skin'], true),
			'S_UCP_ACTION'	=> $this->u_action,
		]);
	}
}
