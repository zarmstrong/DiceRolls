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
 * Set up listener.
 */
class setup_listener implements EventSubscriberInterface
{
	/**
	 * Assign functions defined in this class to event listeners in the core.
	 *
	 * @static
	 * @return array
	 * @access public
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup_after'		=> 'setup_lang',
			'core.page_header'			=> 'setup_links',
			'core.permissions'			=> 'setup_permissions',
		);
	}

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
	public function __construct(\phpbb\auth\auth $auth, \phpbbstudio\dice\core\functions_common $functions, \phpbb\language\language $lang, \phpbb\template\template $template)
	{
		$this->auth			= $auth;
		$this->functions	= $functions;
		$this->lang			= $lang;
		$this->template		= $template;
	}

	/**
	 * Load extension language file during user set up.
	 *
	 * @event  core.user_setup_after
	 * @return void
	 * @access public
	 */
	public function setup_lang()
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
	public function setup_links()
	{
		$template_vars = array();

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
	public function setup_permissions($event)
	{
		/* Assigning them to local variables first */
		$permissions = $event['permissions'];
		$categories = $event['categories'];

		/* Setting up a new permissions's CAT for us */
		if (!isset($categories['phpbb_studio']))
		{
			$categories['phpbb_studio']= 'ACL_CAT_PHPBB_STUDIO';
		}

		$permissions += [
			'f_dice_roll' => [
				'lang'	=> 'ACL_F_DICE_ROLL',
				'cat'	=> 'phpbb_studio',
			],
			'f_dice_edit' => [
				'lang'	=> 'ACL_F_DICE_EDIT',
				'cat'	=> 'phpbb_studio',
			],

			'f_dice_delete' => [
				'lang'	=> 'ACL_F_DICE_DELETE',
				'cat'	=> 'phpbb_studio',
			],
			'f_dice_view' => [
				'lang'	=> 'ACL_F_DICE_VIEW',
				'cat'	=> 'phpbb_studio',
			],
			'f_dice_no_limit' => [
				'lang'	=> 'ACL_F_DICE_NO_LIMIT',
				'cat'	=> 'phpbb_studio',
			],
			'f_mod_dice_add' => [
				'lang'	=> 'ACL_F_MOD_DICE_ADD',
				'cat'	=> 'phpbb_studio',
			],
			'f_mod_dice_edit' => [
				'lang'	=> 'ACL_F_MOD_DICE_EDIT',
				'cat'	=> 'phpbb_studio',
			],
			'f_mod_dice_delete' => [
				'lang'	=> 'ACL_F_MOD_DICE_DELETE',
				'cat'	=> 'phpbb_studio',
			],
			'a_dice_admin' => [
				'lang'	=> 'ACL_A_DICE_ADMIN',
				'cat'	=> 'phpbb_studio',
			],
			'u_dice_use_ucp' => [
				'lang'	=> 'ACL_U_DICE_USE_UCP',
				'cat'	=> 'phpbb_studio',
			],
			'u_dice_test' => [
				'lang'	=> 'ACL_U_DICE_TEST',
				'cat'	=> 'phpbb_studio',
			],
			'u_dice_skin' => [
				'lang'	=> 'ACL_U_DICE_SKIN',
				'cat'	=> 'phpbb_studio',
			],
		];

		/* Merging our CAT to the native array of perms */
		$event['categories'] = array_merge($event['categories'], $categories);

		/* Copying back to event variable */
		$event['permissions'] = $permissions;
	}
}
