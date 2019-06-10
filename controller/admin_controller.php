<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\controller;

/**
 * phpBB Studio's Dice Admin controller.
 */
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\language\language */
	protected $lang;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\user_loader */
	protected $user_loader;

	/** @var string Forums table */
	protected $forums_table;

	/** @var string Dice rolls table*/
	protected $rolls_table;

	/** @var string Topics table */
	protected $topics_table;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php File extension */
	protected $php_ext;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\config\config						$config			Configuration object
	 * @param  \phpbb\db\driver\driver_interface		$db				Database object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Common dice functions
	 * @param  \phpbb\language\language					$lang			Language object
	 * @param  \phpbb\log\log							$log			Log object
	 * @param  \phpbb\request\request					$request		Request object
	 * @param  \phpbb\template\template					$template		Template object
	 * @param  \phpbb\user								$user			User object
	 * @param  \phpbb\user_loader						$user_loader	User loader object
	 * @param  string									$forums_table	Forums table
	 * @param  string									$rolls_table	Dice rolls table
	 * @param  string									$topics_table	Topics table
	 * @param  string									$root_path		phpBB root path
	 * @param  string									$php_ext		phpEx
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\language\language $lang,
		\phpbb\log\log $log,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\user_loader $user_loader,
		$forums_table,
		$rolls_table,
		$topics_table,
		$root_path,
		$php_ext
	)
	{
		$this->config		= $config;
		$this->db			= $db;
		$this->functions	= $functions;
		$this->lang			= $lang;
		$this->log			= $log;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->user_loader	= $user_loader;
		$this->forums_table	= $forums_table;
		$this->rolls_table	= $rolls_table;
		$this->topics_table	= $topics_table;

		$this->root_path	= $root_path;
		$this->php_ext		= $php_ext;
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle($action)
	{
		// Requests
		$submit = $this->request->is_set_post('submit');
		$value = $this->request->variable('value', '');

		// Add a form key to the settings
		$form_key = 'dice_settings';
		add_form_key($form_key);

		// Set up config settings
		$options = [
			'dice_sides_only',
			'dice_max_rolls',
			'dice_per_notation',
			'dice_qty_per_dice',
			'dice_qty_dice_per_notation',
			'dice_sides_per_dice',
			'dice_pc_dice_per_notation',
			'dice_fudge_dice_per_notation',
			'dice_penetration_dice_per_notation',
			'dice_compound_dice_per_notation',
			'dice_exploding_dice_per_notation',
		];

		// Assign config settings
		$template_vars = [];
		foreach ($options as $option)
		{
			$template_vars[utf8_strtoupper($option)] = $this->config[$option];
		}

		// Get the roll count from the database
		$rolls_total = $this->db->get_estimated_row_count($this->rolls_table);

		// Get the orphaned roll count from the database
		$sql = 'SELECT COUNT(roll_id) as orphans FROM ' . $this->rolls_table . ' WHERE roll_id = 0 OR post_id = 0 OR topic_id = 0 OR forum_id = 0 OR user_id = 0';
		$result = $this->db->sql_query($sql);
		$rolls_orphan = $this->db->sql_fetchfield('orphans');
		$this->db->sql_freeresult($result);

		// Select the top topics with the most rolls
		$sql_array = [
			'SELECT'	=> 'COUNT(r.roll_id) as total,
							t.topic_id, t.topic_title,
							f.forum_id, f.forum_name',
			'FROM'		=> [$this->rolls_table => 'r'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [$this->topics_table => 't'],
					'ON'	=> 't.topic_id = r.topic_id',
				],
				[
					'FROM'	=> [$this->forums_table => 'f'],
					'ON'	=> 'f.forum_id = r.forum_id',
				],
			],
			'GROUP_BY'	=> 'r.topic_id, t.topic_title, f.forum_id, f.forum_name',
			'ORDER_BY'	=> 'total DESC',
		];

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, 8);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('topics', [
				'FORUM_NAME'	=> $row['forum_name'],
				'TOPIC_TITLE'	=> $row['topic_title'],
				'TOTAL'			=> (int) $row['total'],

				'U_FORUM'		=> append_sid($this->root_path . 'viewforum.' . $this->php_ext, ['f' => (int) $row['forum_id']]),
				'U_TOPIC'		=> append_sid($this->root_path . 'viewtopic.' . $this->php_ext, ['f' => (int) $row['forum_id'], 't' => (int) $row['topic_id']]),
			]);
		}

		$this->db->sql_freeresult($result);

		// Select the top users with the most rolls
		$users = [];

		$sql = 'SELECT COUNT(roll_id) as total, user_id
				FROM ' . $this->rolls_table . '
				WHERE user_id > 0
				GROUP BY user_id
				ORDER BY total DESC';
		$result = $this->db->sql_query_limit($sql, 8);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$users[(int) $row['user_id']] = (int) $row['total'];
		}

		$this->db->sql_freeresult($result);

		// Load the top users in the user_loader
		$this->user_loader->load_users(array_keys($users));

		// Assign the users data to the template
		foreach ($users as $user_id => $total)
		{
			$this->template->assign_block_vars('users', [
				'AVATAR'	=> (string) $this->user_loader->get_avatar($user_id),
				'NAME'		=> (string) $this->user_loader->get_username($user_id, 'full'),
				'TOTAL'		=> (int) $total,
			]);
		}

		// Dice sides
		$sides = $this->functions->get_dice_sides();

		foreach ($sides as $side)
		{
			$this->template->assign_block_vars('sides', [
				'NUMBER'	=> $side,
				'U_DELETE'	=> $this->u_action . '&action=side_delete&value=' . $side,
			]);
		}

		// Dice skins
		$skins = $this->functions->get_dice_skins();
		$skins_dir_valid = $this->functions->check_dice_skins_dir();

		$skins_all_valid = true;

		if ($skins_dir_valid)
		{
			$skins_available = $this->functions->available_dice_skins();

			foreach($skins_available as $skin)
			{
				$valid = (bool) $this->functions->validate_dice_skin($skin, $sides);
				$installed = (bool) in_array($skin, $skins);

				$skins_all_valid = ($installed && !$valid) ? false : $skins_all_valid;

				$this->template->assign_block_vars('skins', [
					'NAME'			=> (string) $skin,
					'S_INSTALLED'	=> (bool) $installed,
					'S_VALID'		=> (bool) $valid,
					'U_ACTION'		=> $this->u_action . '&action=skin_' . ($installed ? 'uninstall' : 'install') . '&value=' . $skin,
				]);
			}
			/**
			 * Any skins installed but not found in the filesystem should be automatically removed.
			 *
			 * Skins that are in the installed list and not in the filesystem
			 */
			$skins_difference = array_diff($skins, $skins_available);

			if (!empty($skins_difference))
			{
				$this->skins_update('skin_uninstall', $skins, $skins_difference);
			}
		}

		// Garbage collection
		$errors = [];

		// If the settings were submitted
		if ($submit)
		{
			if (!check_form_key($form_key))
			{
				$errors[] = $this->lang->lang('FORM_INVALID');
			}

			// Request the options
			$option_sides_only = $this->request->variable('dice_sides_only', (bool) $this->config['dice_sides_only']);
			$option_skins_height = (int) $this->request->variable('dice_skins_img_height', (int) $this->config['dice_skins_img_height']);
			$option_skins_width = (int) $this->request->variable('dice_skins_img_width', (int) $this->config['dice_skins_img_width']);

			$option_skins_dir = $this->request->variable('dice_skins_dir', (string) $this->config['dice_skins_dir'], true);

			/**
			 * Let's trim a bunch of characters from the beginning and end of a string
			 *
			 * https://www.php.net/manual/en/function.trim.php
			 *
			 * " " (ASCII 32 (0x20)), an ordinary space.
			 *  "\t" (ASCII 9 (0x09)), a tab.
			 * "\n" (ASCII 10 (0x0A)), a new line (line feed).
			 * "\r" (ASCII 13 (0x0D)), a carriage return.
			 * "\0" (ASCII 0 (0x00)), the NUL-byte.
			 * "\x0B" (ASCII 11 (0x0B)), a vertical tab.
			 *
			 * Added "/"
			 */
			$option_skins_dir = trim($option_skins_dir, "/ \t\n\r\0\x0B");

			/**
			 * Let's harden all of this a bit more
			 *
			 * Especially Dots, Backslashes plus a bunch of other chars aren't allowed.
			 */
			if (preg_match_all('/[\\\:*<>|"]|\.{2,}|^\./', $option_skins_dir, $matches))
			{
				$character_list = implode('<br>', $matches[0]);

				$errors[] = $this->lang->lang('ACP_DICE_SKINS_PATH_ERROR', $character_list);
			}

			// Check if directory exists
			if (!$this->functions->check_dice_dir($this->functions->make_dice_dir($option_skins_dir)))
			{
				$errors[] = $this->lang->lang('DIRECTORY_DOES_NOT_EXIST', $option_skins_dir);
			}

			// Check if skin images are within the 16 - 80 pixels
			if ($option_skins_height < 16 || $option_skins_height > 80)
			{
				$errors[] = $this->lang->lang('ACP_DICE_SKINS_IMG_HEIGHT_ERROR');
			}

			if ($option_skins_width < 16 || $option_skins_width > 80)
			{
				$errors[] = $this->lang->lang('ACP_DICE_SKINS_IMG_WIDTH_ERROR');
			}

			// No errors? Lets start saving
			if (empty($errors))
			{
				if ($option_skins_dir != $this->config['dice_skins_dir'])
				{
					$this->config->set('dice_skins_dir', $option_skins_dir);
				}

				if ($option_skins_height != $this->config['dice_skins_img_height'])
				{
					$this->config->set('dice_skins_img_height', $option_skins_height);
				}

				if ($option_skins_width != $this->config['dice_skins_img_width'])
				{
					$this->config->set('dice_skins_img_width', $option_skins_width);
				}

				if ($option_sides_only != $this->config['dice_sides_only'])
				{
					$this->config->set('dice_sides_only', $option_sides_only);
				}

				$option_numbers = [
					'dice_max_rolls',
					'dice_per_notation',
					'dice_qty_per_dice',
					'dice_qty_dice_per_notation',
					'dice_sides_per_dice',
					'dice_pc_dice_per_notation',
					'dice_fudge_dice_per_notation',
					'dice_penetration_dice_per_notation',
					'dice_compound_dice_per_notation',
					'dice_exploding_dice_per_notation',
				];

				foreach ($option_numbers as $n)
				{
					$setting = (int) $this->config[$n];
					$value = (int) $this->request->variable($n, $setting);

					if ($value !== $setting)
					{
						$this->config->set($n, $value);
					}
				}

				// Log it
				$this->log->add('admin', $this->user->data['user_id'], $this->user->data['user_ip'], 'LOG_ACP_DICE_SETTINGS');

				// Success message
				trigger_error($this->lang->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
			}
		}

		// Process any action
		if ($action)
		{
			switch ($action)
			{
				case 'example':
					$this->template->set_filenames(['example' => '@phpbbstudio_dice/dice_example.html']);
					$this->template->assign_var('S_IS_AJAX', $this->request->is_ajax());

					if ($this->request->is_ajax())
					{
						$json_response = new \phpbb\json_response;
						$json_response->send([
							'MESSAGE_TITLE' => $this->lang->lang('INFORMATION'),
							'MESSAGE_TEXT'  => $this->template->assign_display('example'),
						]);
					}
				break;

				case 'locations':
					// Assign the link locations
					$this->assign_link_locations();

					if ($this->request->is_set_post('submit_locations'))
					{
						// Request the link locations
						$links = $this->request_link_locations();

						// Set the link locations
						$this->functions->set_dice_link_locations($links);

						// Log it
						$this->log->add('admin', $this->user->data['user_id'], $this->user->data['user_ip'], 'LOG_ACP_DICE_LOCATIONS');

						// Success message
						trigger_error($this->lang->lang('ACP_DICE_LOCATIONS_SUCCESS') . '<br>' . adm_back_link($this->u_action));
					}
				break;

				default:
					if (confirm_box(true))
					{
						switch ($action)
						{
							case 'orphaned':
								$value = $this->delete_orphans();
							break;

							case 'side_add':
							case 'side_delete':
								$this->sides_update($action, $sides, $value);
							break;

							case 'skin_install':
							case 'skin_uninstall':
								$this->skins_update($action, $skins, $value);
							break;
						}

						// Log it
						$this->log->add('admin', $this->user->data['user_id'], $this->user->data['user_ip'], 'LOG_ACP_DICE_' . utf8_strtoupper($action), false, [$value]);

						// Show success message
						trigger_error($this->lang->lang('ACP_DICE_' . utf8_strtoupper($action) . '_SUCCESS', $value) . '<br>' . adm_back_link($this->u_action));
					}
					else
					{
						confirm_box(false, 'ACP_DICE_' . utf8_strtoupper($action), build_hidden_fields([
							'action' => $action,
							'value'  => $value,
						]));
					}
				break;
			}
		}

		$this->template->assign_vars(array_merge($template_vars, [
			'S_ERRORS'			=> !empty($errors),
			'ERROR_MSG'			=> implode('<br>', $errors),

			'SKINS_DIR'			=> $this->config['dice_skins_dir'],
			'SKINS_DIR_ERROR'	=> !$skins_dir_valid,
			'SKINS_IMG_HEIGHT'	=> $this->config['dice_skins_img_height'],
			'SKINS_IMG_WIDTH'	=> $this->config['dice_skins_img_width'],
			'SKINS_INSTALLED'	=> count($skins),
			'SKINS_VALID'		=> $skins_all_valid,
			'ROLLS_TOTAL'		=> $rolls_total,
			'ROLLS_ORPHAN'		=> $rolls_orphan,

			'U_ACTION'			=> $this->u_action . '#dice_settings',
			'U_BACK'			=> $this->u_action,
			'U_EXAMPLE'			=> $this->u_action . '&action=example',
			'U_LOCATIONS'		=> $this->u_action . '&action=locations',
			'U_ORPHANED'		=> $this->u_action . '&action=orphaned',
			'U_SIDE_ADD'		=> $this->u_action . '&action=side_add',
		]));
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

	/**
	 * Delete all orphaned dice rolls.
	 *
	 * @return int						Amount of dice rolls that were deleted
	 * @access public
	 */
	protected function delete_orphans()
	{
		$sql = 'DELETE FROM ' . $this->rolls_table . ' WHERE roll_id = 0 OR post_id = 0 OR topic_id = 0 OR forum_id = 0 OR user_id = 0';
		$this->db->sql_query($sql);

		return (int) $this->db->sql_affectedrows();
	}

	/**
	 * Update the installed dice sides setting.
	 *
	 * @param  string	$action		The action to take (side_add|side_delete)
	 * @param  array	$sides		Array of currently installed dice sides
	 * @param  int		$value		The dice side to add or delete
	 * @return void
	 * @access protected
	 */
	protected function sides_update($action, array $sides, $value)
	{
		switch ($action)
		{
			case 'side_add':
				$sides[] = (int) $value;
			break;

			case 'side_delete':
				if (($key = array_search($value, $sides)) !== false)
				{
					unset($sides[$key]);
				}
			break;
		}

		// Clean the array (filter, unique, sort)
		$sides = $this->functions->clean_dice_array($sides);

		// Store the new array in the database
		$this->functions->set_dice_sides($sides);
	}

	/**
	 * Update the installed dice skin setting.
	 *
	 * @param  string	$action		The action to take (skin_install|skin_uninstall)
	 * @param  array	$skins		Array of currently installed dice skins
	 * @param  mixed	$value		The value/array to install (add) or uninstall (delete)
	 * @return void
	 * @access protected
	 */
	protected function skins_update($action,array  $skins, $value)
	{
		// Force an array, if it is not already
		$value = is_array($value) ? (array) $value : (array) [$value];

		switch ($action)
		{
			case 'skin_install':
				$skins = array_merge($skins, $value);
			break;

			case 'skin_uninstall':
				$skins = array_diff($skins, $value);
			break;
		}

		// Clean the array (filter, unique, sort)
		$skins = $this->functions->clean_dice_array($skins);

		// Store the new array in the database
		$this->functions->set_dice_skins($skins);
	}

	/**
	 * Assign link locations.
	 *
	 * @return void
	 * @access protected
	 */
	protected function assign_link_locations()
	{
		$template_vars = [];
		$locations = $this->functions->get_dice_link_locations();

		foreach ($locations as $location)
		{
			$template_vars[$location['name']] = (bool) $location['status'];
		}

		$this->template->assign_vars($template_vars);
	}

	/**
	 * Request link locations
	 *
	 * @return array					Array with link locations and their status
	 * @access protected
	 */
	protected function request_link_locations()
	{
		$links = [];
		$locations = $this->functions->get_dice_link_locations();

		foreach ($locations as $location)
		{
			$links[$location['name']] = $this->request->variable((string) $location['name'], false);
		}

		return $links;
	}
}
