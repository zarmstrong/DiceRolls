<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * phpBB Studio's Dice Set up listener.
 */
class setup_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\language\language */
	protected $lang;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\auth\auth							$auth			Authentication object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Common dice functions
	 * @param  \phpbb\language\language					$lang			Language object
	 * @param  \phpbb\template\template					$template		Template object
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\language\language $lang,
		\phpbb\template\template $template
	)
	{
		$this->auth			= $auth;
		$this->functions	= $functions;
		$this->lang			= $lang;
		$this->template		= $template;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core.
	 *
	 * @static
	 * @return array
	 * @access public
	 */
	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup_after'		=> 'dice_setup_lang',
			'core.page_header'			=> 'dice_setup_links',
			'core.permissions'			=> 'dice_setup_permissions',
		];
	}

	/**
	 * Load extension language file during user set up.
	 *
	 * @event  core.user_setup_after
	 * @return void
	 * @access public
	 */
	public function dice_setup_lang()
	{
		$this->lang->add_lang('dice_common', 'phpbbstudio/dice');
	}

	/**
	 * Set up dice page links.
	 *
	 * @event  core.page_header
	 * @return void
	 * @access public
	 */
	public function dice_setup_links()
	{
		$template_vars = [];

		// If the user has the permission to view the page
		if ($this->auth->acl_get('u_dice_test'))
		{
			foreach ($this->functions->get_dice_link_locations() as $link)
			{
				// Lets only add those links that are enabled to the template
				if ($link['status'])
				{
					$template_vars['S_DICE_' . utf8_strtoupper($link['name'])] = true;
				}
			}

			$this->template->assign_vars($template_vars);
		}
	}

	/**
	 * Add permissions for DICE - Permission's language file is automatically loaded.
	 *
	 * @event  core.permissions
	 * @param  \phpbb\event\data		$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_setup_permissions($event)
	{
		$categories = $event['categories'];
		$permissions = $event['permissions'];

		if (empty($categories['phpbb_studio']))
		{
			/* Setting up a custom CAT */
			$categories['phpbb_studio'] = 'ACL_CAT_PHPBB_STUDIO';

			$event['categories'] = $categories;
		}

		$perms = [
			'f_dice_roll',
			'f_dice_edit',
			'f_dice_delete',
			'f_dice_view',
			'f_dice_no_limit',
			'f_mod_dice_add',
			'f_mod_dice_edit',
			'f_mod_dice_delete',
			'a_dice_admin',
			'u_dice_use_ucp',
			'u_dice_test',
			'u_dice_skin',
		];

		foreach ($perms as $permission)
		{
			$permissions[$permission] = ['lang' => 'ACL_' . utf8_strtoupper($permission), 'cat' => 'phpbb_studio'];
		}

		$event['permissions'] = $permissions;
	}
}
