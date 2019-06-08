<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\controller;

/**
 * phpBB Studio's Dice Main controller.
 */
class main_controller implements main_interface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $lang;

	/** @var \phpbbstudio\dice\operator\roll */
	protected $operator;

	/** @var \phpbbstudio\dice\core\functions_regex */
	protected $regex;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\auth\auth							$auth			Authentication object
	 * @param  \phpbb\config\config						$config			Configuration object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Common functions object
	 * @param  \phpbb\controller\helper					$helper			Controller helper object
	 * @param  \phpbb\language\language					$lang			Language object
	 * @param  \phpbbstudio\dice\operator\roll			$operator		Roll operator object
	 * @param  \phpbbstudio\dice\core\functions_regex	$regex			Regex functions
	 * @param  \phpbb\request\request					$request		Request object
	 * @param  \phpbb\template\template					$template		Template object
	 * @param  \phpbb\user								$user			User object
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $lang,
		\phpbbstudio\dice\operator\roll $operator,
		\phpbbstudio\dice\core\functions_regex $regex,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user
	)
	{
		$this->auth			= $auth;
		$this->config		= $config;
		$this->functions	= $functions;
		$this->helper		= $helper;
		$this->lang			= $lang;
		$this->operator		= $operator;
		$this->regex		= $regex;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add($forum_id, $topic_id, $post_id, $poster_id, $hash)
	{
		if (!$this->request->is_ajax())
		{
			throw new \phpbb\exception\http_exception(400, 'DICE_NOT_AJAX');
		}

		// Set up JSON response
		$json_response = new \phpbb\json_response;

		// Check the link hash for security
		if (!check_link_hash($hash, 'dice_add'))
		{
			$json_response->send([
				'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $this->lang->lang('FORM_INVALID'),
			]);
		}

		// Check if dice is enabled on this forum
		if (!$this->functions->forum_enabled($forum_id))
		{
			$json_response->send([
				'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $this->lang->lang('DICE_ROLL_FORUM_DISABLED'),
			]);
		}

		// Grab the author for this post, if we are not creating a new post
		$poster_id = $poster_id ? $poster_id : $this->user->data['user_id'];

		if (!$this->functions->dice_auth_add($forum_id, (int) $poster_id))
		{
			$json_response->send([
				'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $this->lang->lang('DICE_ROLL_ADD_UNAUTH'),
			]);
		}

		// Set up limit
		$count = 0;

		if ($this->functions->dice_limit($forum_id))
		{
			$entities = $this->operator->get_rolls_for_posting($forum_id, $topic_id, $post_id);
			$count = count($entities);

			if ($this->functions->dice_limit_reached($count, $forum_id))
			{
				$json_response->send([
					'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
					'MESSAGE_TEXT'	=> $this->lang->lang('DICE_ROLLS_TOO_MANY'),
				]);
			}
		}

		// Set up variables
		$user_id = (int) $this->user->data['user_id'];
		$notation = $this->request->variable('notation', '');
		$notation = htmlspecialchars_decode($notation, ENT_COMPAT);

		/**
		 * Get a roll entity from the operator.
		 * @var \phpbbstudio\dice\entity\roll $entity
		 */
		$entity = $this->operator->get_entity();

		// Map out entity functions and data
		$map_fields = [
			'set_forum'		=> $forum_id,
			'set_topic'		=> $topic_id,
			'set_post'		=> $post_id,
			'set_user'		=> $user_id,
			'set_time'		=> time(),
			'set_notation'	=> $notation,
		];

		// Garbage collection
		$errors = [];

		// Call all functions with the respective data
		foreach ($map_fields as $entity_function => $entity_data)
		{
			try
			{
				$entity->$entity_function($entity_data);
			}
			catch (\phpbbstudio\dice\exception\base $e)
			{
				$errors[] = $e->get_message($this->lang);
			}
		}

		// Unset temporarily variables
		unset($map_fields);

		// Try and roll the dice
		if (empty($errors))
		{
			try
			{
				$entity->roll();
			}
			catch (\phpbbstudio\dice\exception\base $e)
			{
				$errors[] = $e->get_message($this->lang);
			}
		}

		if (empty($errors))
		{
			try
			{
				// Insert the roll data to the database
				$entity->insert();

				$count++;
			}
			catch (\phpbbstudio\dice\exception\out_of_bounds $e)
			{
				$errors[] = $e->get_message($this->lang);
			}
		}

		// Send a json response
		$json_response->send([
			'ROLL_SUCCESS'	=> empty($errors),
			'ROLL_LIMIT'	=> (bool) $this->functions->dice_limit_reached($count, $forum_id),
			'ROLL_DATA'		=> $this->operator->get_roll_data_for_edit($entity),
			'MESSAGE_TITLE'	=> empty($errors) ? $this->lang->lang('SUCCESS') : $this->lang->lang('ERROR'),
			'MESSAGE_TEXT'	=> empty($errors) ? $this->lang->lang('DICE_ROLL_ADD_SUCCESS') : implode('<br>', $errors),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function edit($roll_id)
	{
		if (!$this->request->is_ajax())
		{
			throw new \phpbb\exception\http_exception(400, 'DICE_NOT_AJAX');
		}

		// Set up a JSON response
		$json_response = new \phpbb\json_response;

		/**
		 * Get a roll entity from the operator.
		 * @var \phpbbstudio\dice\entity\roll $entity
		 */
		$entity = $this->operator->get_entity();

		try
		{
			$entity->load($roll_id);
		}
		catch (\phpbbstudio\dice\exception\out_of_bounds $e)
		{
			$json_response->send([
				'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $e->get_message($this->lang),
			]);
		}

		// Grab the author for this post, if we are not creating a new post
		$poster_id = $entity->get_post() ? $this->operator->get_author($entity->get_id()) : $this->user->data['user_id'];

		if (!$this->functions->dice_auth_edit($entity->get_forum(), (int) $poster_id))
		{
			$json_response->send([
				'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $this->lang->lang('DICE_ROLL_EDIT_UNAUTH'),
			]);
		}

		if (confirm_box(true))
		{
			// Garbage collection
			$errors = [];

			// Set up variables
			$user_id = (int) $this->user->data['user_id'];
			$notation = $this->request->variable('notation', '');
			$notation = htmlspecialchars_decode($notation, ENT_COMPAT);

			try
			{
				$entity->set_notation($notation)->roll();
			}
			catch (\phpbbstudio\dice\exception\base $e)
			{
				$errors[] = $e->get_message($this->lang);
			}

			if (empty($errors))
			{
				try
				{
					// Update the roll data in the database
					$entity->set_edit_user($user_id)
							->set_edit_time(time())
							->increment_edit_count()
							->save();
				}
				catch (\phpbbstudio\dice\exception\out_of_bounds $e)
				{
					$errors[] = $e->get_message($this->lang);
				}
			}

			// Send a json response
			$json_response->send([
				'ROLL_SUCCESS'	=> empty($errors),
				'ROLL_DATA'		=> $this->operator->get_roll_data_for_edit($entity),
				'MESSAGE_TITLE'	=> empty($errors) ? $this->lang->lang('SUCCESS') : $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> empty($errors) ? $this->lang->lang('DICE_ROLL_EDIT_SUCCESS') : implode('<br>', $errors),
			]);
		}
		else
		{
			$this->template->assign_vars([
				'ROLL_DATA'		=> $this->operator->get_roll_data_for_edit($entity),
			]);

			confirm_box(false, 'DICE_ROLL_EDIT', build_hidden_fields([
				'roll_id'	=> $roll_id,
			]), '@phpbbstudio_dice/dice_edit.html', $this->helper->get_current_url());
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($roll_id)
	{
		if (!$this->request->is_ajax())
		{
			throw new \phpbb\exception\http_exception(400, 'DICE_NOT_AJAX');
		}

		// Set up a JSON response
		$json_response = new \phpbb\json_response;

		/**
		 * Get and load entity
		 * @var \phpbbstudio\dice\entity\roll $entity
		 */
		$entity = $this->operator->get_entity()->load($roll_id);

		// Grab the author for this post, if we are not creating a new post
		$poster_id = $entity->get_post() ? $this->operator->get_author($entity->get_id()) : $this->user->data['user_id'];

		if (!$this->functions->dice_auth_delete($entity->get_forum(), (int) $poster_id))
		{
			$json_response->send([
				'MESSAGE_TITLE'	=> $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $this->lang->lang('DICE_ROLL_DELETE_UNAUTH'),
			]);
		}

		if (confirm_box(true))
		{
			// Delete the roll entity
			$success = $this->operator->delete($roll_id);

			$count = 0;

			if ($success)
			{
				$entities = $this->operator->get_rolls_for_posting($entity->get_forum(), $entity->get_topic(), $entity->get_post());
				$count = count($entities);
			}

			$json_response->send([
				'ROLL_ID'		=> (int) $roll_id,
				'ROLL_SUCCESS'	=> (bool) $success,
				'ROLL_LIMIT'	=> (bool) $this->functions->dice_limit_reached($count, $entity->get_forum()),
				'MESSAGE_TITLE'	=> $success ? $this->lang->lang('SUCCESS') : $this->lang->lang('ERROR'),
				'MESSAGE_TEXT'	=> $success ? $this->lang->lang('DICE_ROLL_DELETE_SUCCESS') : $this->lang->lang('DICE_ROLL_NOT_EXIST', 1),
			]);
		}
		else
		{
			/**
			 * The 5th parameter (u_action) has to be set
			 * for it to work correctly with AJAX and URL rewriting.
			 */
			confirm_box(false, 'DICE_ROLL_DELETE', build_hidden_fields([
				'roll_id'	=> (int) $roll_id,
			]), 'confirm_body.html', $this->helper->get_current_url());
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function page()
	{
		if (!$this->auth->acl_get('u_dice_test'))
		{
			throw new \phpbb\exception\http_exception(403, 'NOT_AUTHORISED');
		}

		$notation = $this->request->variable('notation', '', true);
		$notation = htmlspecialchars_decode($notation, ENT_COMPAT);
		$submit = $this->request->is_set_post('submit');

		/** @var \phpbbstudio\dice\entity\roll $entity */
		$entity = $this->operator->get_entity();

		if ($notation)
		{
			try
			{
				$entity->set_notation($notation)->roll();
			}
			catch (\phpbbstudio\dice\exception\unexpected_value $e)
			{
				$errors[] = $e->get_message($this->lang);
			}
		}

		if ($submit && $this->request->is_ajax())
		{
			$json_response = new \phpbb\json_response;

			$json_response->send([
				'MESSAGE_TITLE'	=> 'HI',
				'MESSAGE_TEXT'	=> $entity->get_output(),
			]);
		}

		$skin = $this->request->variable('skin', $this->user->data['dice_u_skin'], true);
		$skins = $this->functions->get_dice_skins(true);
		$skin_data = $this->functions->get_dice_skin_data(true, $skin);
		$skin_options = $this->functions->build_dice_select($skins, $skin, true);

		$this->template->assign_vars([
			'NOTATION'			=> $entity->get_notation(),
			'OUTPUT'			=> $entity->get_output(),
			'DISPLAY'			=> $entity->get_display($skin_data['name'], $skin_data['dir'], $skin_data['ext']),
			'TOTAL'				=> $entity->get_total(),

			'DICE_IMG_HEIGHT'	=> (int) $this->config['dice_skins_img_height'],
			'DICE_IMG_WIDTH'	=> (int) $this->config['dice_skins_img_width'],

			'DICE_ALLOWED_ONLY'		=> $this->config['dice_sides_only'],
			'DICE_ALLOWED_SIDES'	=> $this->functions->get_dice_sides(),

			'LIMIT_DICE_ROLLS'		=> $this->config['dice_max_rolls'],
			'LIMIT_DICE_QTY'		=> $this->config['dice_qty_per_dice'],
			'LIMIT_DICE_SIDES'		=> $this->config['dice_sides_per_dice'],
			'LIMIT_DICE_FUDGE'		=> $this->config['dice_fudge_dice_per_notation'],
			'LIMIT_DICE_PC'			=> $this->config['dice_pc_dice_per_notation'],
			'LIMIT_DICE_EXPLODE'	=> $this->config['dice_exploding_dice_per_notation'],
			'LIMIT_DICE_PEN'		=> $this->config['dice_penetration_dice_per_notation'],
			'LIMIT_DICE_COMP'		=> $this->config['dice_compound_dice_per_notation'],

			'SKIN_OPTIONS'			=> $skin_options,

			'S_DICE_SUBMIT'			=> $submit,

			'U_DICE_ACTION'			=> $this->helper->route('phpbbstudio_dice'),
		]);

		return $this->helper->render('@phpbbstudio_dice/dice_page.html', $this->lang->lang('DICE_ROLL'));
	}
}
