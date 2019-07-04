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
 * phpBB Studio's Dice Display listener.
 */
class display_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbbstudio\dice\operator\roll */
	protected $operator;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\auth\auth							$auth			Authentication object
	 * @param  \phpbb\config\config						$config			Configuration object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Common functions
	 * @param  \phpbbstudio\dice\operator\roll			$operator		Roll operator object
	 * @param  \phpbb\template\template					$template		Template object
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbbstudio\dice\operator\roll $operator,
		\phpbb\template\template $template
	)
	{
		$this->auth			= $auth;
		$this->config		= $config;
		$this->functions	= $functions;
		$this->operator		= $operator;
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
			'core.viewtopic_assign_template_vars_before'	=> 'set_dice_display',
			'core.viewtopic_modify_post_data'				=> 'get_dice_rolls',
			'core.viewtopic_modify_post_row'				=> 'display_dice_rolls',
			'core.topic_review_modify_post_list'			=> 'get_review_dice_rolls',
			'core.topic_review_modify_row'					=> 'display_review_dice_rolls',
			'core.mcp_topic_modify_post_data'				=> 'get_mcp_dice_rolls',
			'core.mcp_topic_review_modify_row'				=> 'display_mcp_dice_rolls',
			'core.mcp_post_template_data'					=> 'display_mcp_post_dice_rolls',
			'core.mcp_report_template_data'					=> 'display_mcp_report_dice_rolls',
		];
	}

	/**
	 * Assign template variables used for displaying dice rolls.
	 *
	 * @event  core.viewtopic_assign_template_vars_before
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function set_dice_display($event)
	{
		$forum_id	= (int) $event['forum_id'];		// Forum identifier
		$topic_data = (array) $event['topic_data'];	// Array with topic data

		$this->template->assign_vars([
			'DICE_IMG_HEIGHT'	=> (int) $this->config['dice_skins_img_height'],
			'DICE_IMG_WIDTH'	=> (int) $this->config['dice_skins_img_width'],
			'S_DICE_DISPLAY'	=> (bool) ($topic_data['dice_enabled'] && $this->auth->acl_get('f_dice_view', $forum_id)),
		]);
	}

	/**
	 * Get roll data for a list of posts in a topic page.
	 *
	 * @event  core.viewtopic_modify_post_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function get_dice_rolls($event)
	{
		// Grab event data
		$forum_id	= (int) $event['forum_id'];		// Forum identifier
		$topic_id	= (int) $event['topic_id'];		// Topic identifier
		$topic_data	= (array) $event['topic_data'];	// Array with topic data
		$post_list	= (array) $event['post_list'];	// Array with post_ids we are going to display

		// If the dice extension is not enabled for this forum, we return.
		if (!$topic_data['dice_enabled'] || !$this->auth->acl_get('f_dice_view', $forum_id))
		{
			return;
		}

		// Get entities for the post list
		$entities = $this->operator->get_rolls_for_topic($forum_id, $topic_id, $post_list);

		// Get the dice skin
		$skin_data = $this->functions->get_dice_skin_data($topic_data['dice_skin_override'], $topic_data['dice_f_skin']);

		$topic_data['dice_skin'] = $skin_data;

		/** @var \phpbbstudio\dice\entity\roll $entity */
		foreach ($entities as $entity)
		{
			$roll_id = (int) $entity->get_id();		// Roll identifier

			// Add the roll data to the topic data
			$topic_data['dice_rolls'][$roll_id] = $this->operator->get_roll_data_for_display($entity, $skin_data);
		}

		$event['topic_data'] = $topic_data;
	}

	/**
	 * Display the roll data for a list of posts in a topic page.
	 *
	 * @event  core.viewtopic_modify_post_row
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function display_dice_rolls($event)
	{
		// Grab the event data
		$row		= (array) $event['row'];		// Array with original post and user data
		$post_row	= (array) $event['post_row'];	// Template block array of the post
		$topic_data = (array) $event['topic_data'];

		// Grab the post message and the rolls data
		$message	= $post_row['MESSAGE'];
		$rolls		= isset($topic_data['dice_rolls']) ? $topic_data['dice_rolls'] : [];

		if ($topic_data['dice_enabled'] && $this->auth->acl_get('f_dice_view', $row['forum_id']))
		{
			$message = $this->replace_rolls($message, $rolls, $topic_data['dice_skin'], $row, false);

			// Lets do the quotes!
			$message = preg_replace_callback(
				'/<blockquote[^>]*>(.+?)<\/blockquote>/s',
				function ($match) use (&$rolls, $topic_data, $row) {
					return $this->replace_rolls($match[0], $rolls, $topic_data['dice_skin'], $row, true);
				},
				$message
			);

			$outline = $this->display_dice_rolls_not_inline($rolls, $row['post_id']);

			$post_row['DICE_ROLLS_OUTLINE'] = $outline;
			$post_row['MESSAGE'] = $message;
			$event['post_row'] = $post_row;

			$topic_data['dice_rolls'] = $rolls;
			$event['topic_data'] = $topic_data;
		}
	}

	/**
	 * Get the dice rolls displayed in the topic review.
	 *
	 * @event  core.topic_review_modify_post_list
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function get_review_dice_rolls($event)
	{
		// Grab event data
		$forum_id	= (int) $event['forum_id'];		// Forum identifier
		$topic_id	= (int) $event['topic_id'];		// Topic identifier
		$post_list	= (array) $event['post_list'];	// Array with post_ids we are going to display
		$rowset		= (array) $event['rowset'];		// Array with the posts data

		$forum_data = $this->functions->forum_data((int) $forum_id);

		// If the dice extension is not enabled for this forum, we return.
		if (!$forum_data['dice_enabled'] || !$this->auth->acl_get('f_dice_view', $forum_id))
		{
			return;
		}

		// Get entities for the post list
		$entities = $this->operator->get_rolls_for_topic($forum_id, $topic_id, $post_list);

		// Get the dice skin
		$skin_data = $this->functions->get_dice_skin_data($forum_data['dice_skin_override'], $forum_data['dice_f_skin']);

		/** @var \phpbbstudio\dice\entity\roll $entity */
		foreach ($entities as $entity)
		{
			// Add the roll data to the topic data
			$rowset[$entity->get_post()]['dice_rolls'][$entity->get_id()] = $this->operator->get_roll_data_for_display($entity, $skin_data);
			$rowset[$entity->get_post()]['dice_skin'] = $skin_data;
		}

		$this->template->assign_var('S_DICE_REVIEW', true);

		$event['rowset'] = $rowset;
	}

	/**
	 * Display the dice rolls in the topic review.
	 *
	 * @event  core.topic_review_modify_row
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function display_review_dice_rolls($event)
	{
		// Grab event data
		$forum_id = (int) $event['forum_id'];
		$post_row = (array) $event['post_row'];
		$row = (array) $event['row'];

		$message = $post_row['MESSAGE'];
		$rolls = isset($row['dice_rolls']) ? $row['dice_rolls'] : [];

		$skin_data = isset($row['dice_skin']) ? $row['dice_skin'] : [];

		if ($this->auth->acl_get('f_dice_view', $forum_id) && $rolls)
		{
			if (!$skin_data)
			{
				$forum_data = $this->functions->forum_data((int) $forum_id);
				$skin_data = $this->functions->get_dice_skin_data($forum_data['dice_skin_override'], $forum_data['dice_f_skin']);
			}

			$message = $this->replace_rolls($message, $rolls, $skin_data, $row, false);

			// Lets do the quotes!
			$message = preg_replace_callback(
				'/<blockquote[^>]*>(.+?)<\/blockquote>/s',
				function ($match) use (&$rolls, $skin_data, $row) {
					return $this->replace_rolls($match[0], $rolls, $skin_data, $row, true);
				},
				$message
			);

			$outline = $this->display_dice_rolls_not_inline($rolls, $row['post_id']);

			$post_row['DICE_ROLLS_OUTLINE'] = $outline;
			$post_row['MESSAGE'] = $message;
			$event['post_row'] = $post_row;
		}
	}

	/**
	 * Get the dice rolls displayed in the Moderator Control Panel.
	 *
	 * @event  core.mcp_topic_modify_post_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function get_mcp_dice_rolls($event)
	{
		// Grab event data
		$forum_id	= (int) $event['forum_id'];			// Forum identifier
		$topic_id	= (int) $event['topic_id'];			// Topic identifier
		$post_list	= (array) $event['post_id_list'];	// Array with post_ids we are going to display
		$rowset		= (array) $event['rowset'];			// Array with the posts data

		// Forum id might not be directly available from the &f= parameter, so otherwise grab it from the first post data.
		$forum_id = (empty($forum_id) && isset($rowset[0]['forum_id'])) ? (int) $rowset[0]['forum_id'] : $forum_id;

		$forum_data = $this->functions->forum_data((int) $forum_id);

		// If the dice extension is not enabled for this forum, we return.
		if (!$forum_data['dice_enabled'] || !$this->auth->acl_get('f_dice_view', $forum_id))
		{
			return;
		}

		$rolls = [];

		// Get entities for the post list
		$entities = $this->operator->get_rolls_for_topic($forum_id, $topic_id, $post_list);

		// Get the dice skin
		$skin_data = $this->functions->get_dice_skin_data($forum_data['dice_skin_override'], $forum_data['dice_f_skin']);

		/** @var \phpbbstudio\dice\entity\roll $entity */
		foreach ($entities as $entity)
		{
			// Add the roll data to the topic data
			$rolls[$entity->get_post()][$entity->get_id()] = $this->operator->get_roll_data_for_display($entity, $skin_data);
		}

		// This rowset does not have post id's as array keys.. :(
		for ($i = 0, $count = count($rowset); $i < $count; $i++)
		{
			$post_id = $rowset[$i]['post_id'];

			if (isset($rolls[$post_id]))
			{
				$rowset[$i]['dice_rolls'] = $rolls[$post_id];
				$rowset[$i]['dice_skin'] = $skin_data;
			}
		}

		$this->template->assign_var('S_DICE_MCP_DISPLAY', true);

		$event['rowset'] = $rowset;
	}

	/**
	 * Display dice rolls in the Moderator Control Panel.
	 *
	 * @event  core.mcp_topic_review_modify_row
	 * @param  \phpbb\event\data	$event		The event object
	 * @access public
	 * @return void
	 */
	public function display_mcp_dice_rolls($event)
	{
		// Grab event data
		$forum_id	= (int) $event['forum_id'];
		$topic_info	= (array) $event['topic_info'];
		$post_row	= (array) $event['post_row'];
		$row		= (array) $event['row'];

		// Forum id might not be directly available from the &f= parameter, so otherwise grab it from the first post data.
		$forum_id = empty($forum_id) ? (int) $row['forum_id'] : $forum_id;

		$message = $post_row['MESSAGE'];

		// Get the dice rolls and merge them with all previously queried from this topic
		$topic_rolls = isset($topic_info['dice_rolls']) ? $topic_info['dice_rolls'] : [];
		$post_rolls = isset($row['dice_rolls']) ? $row['dice_rolls'] : [];
		$rolls = $topic_rolls + $post_rolls;

		// Get the dice skin data: stored in topic data? stored in row data? query.
		if (isset($topic_info['dice_skin']))
		{
			$skin_data = $topic_info['dice_skin'];
		}
		else if (isset($row['dice_skin']))
		{
			$skin_data = $row['dice_skin'];
		}
		else
		{
			$forum_data = $this->functions->forum_data((int) $forum_id);
			$skin_data = $this->functions->get_dice_skin_data($forum_data['dice_skin_override'], $forum_data['dice_f_skin']);
		}

		// Merge the skin data into the topic data
		$topic_info['dice_skin'] = $skin_data;

		if ($this->auth->acl_get('f_dice_view', $forum_id) && $rolls)
		{
			$message = $this->replace_rolls($message, $rolls, $skin_data, $row, false);

			// Lets do the quotes!
			$message = preg_replace_callback(
				'/<blockquote[^>]*>(.+?)<\/blockquote>/s',
				function ($match) use (&$rolls, $skin_data, $row) {
					return $this->replace_rolls($match[0], $rolls, $skin_data, $row, true);
				},
				$message
			);

			$outline = $this->display_dice_rolls_not_inline($rolls, $row['post_id']);

			$post_row['DICE_ROLLS_OUTLINE'] = $outline;
			$post_row['MESSAGE'] = $message;
			$event['post_row'] = $post_row;
		}

		$topic_info['dice_rolls'] = $rolls;
		$event['topic_info'] = $topic_info;
	}

	/**
	 * Display dice rolls in the Moderator Control Panel's post details.
	 *
	 * @event  core.mcp_post_template_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @retrun void
	 * @access public
	 */
	public function display_mcp_post_dice_rolls($event)
	{
		$post_info	= (array) $event['post_info'];				// Array with the post information
		$post_tpl	= (array) $event['mcp_post_template_data'];	// Array with the MCP post template data

		$message = $post_tpl['POST_PREVIEW'];

		$message = $this->replace_mcp_rolls($message, $post_info);

		$post_tpl['POST_PREVIEW'] = $message;
		$event['mcp_post_template_data'] = $post_tpl;
	}

	/**
	 * Display dice rolls in the Moderator Control Panel's report details.
	 *
	 * @event  core.mcp_report_template_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function display_mcp_report_dice_rolls($event)
	{
		// Grab event data
		$post_info	= (array) $event['post_info'];				// Array with the post information
		$report_tpl	= (array) $event['report_template'];		// Array with the MCP report template data

		$message = $report_tpl['POST_PREVIEW'];

		$message = $this->replace_mcp_rolls($message, $post_info);

		$report_tpl['POST_PREVIEW'] = $message;
		$event['report_template'] = $report_tpl;
	}

	/**
	 * Replace dice rolls in a message in the Moderator Control Panel.
	 *
	 * @param  string	$message		The message with the dice rolls
	 * @param  array	$post_info		The array with the post data
	 * @return string					The rendered message with the dice rolls replacement
	 * @access protected
	 */
	protected function replace_mcp_rolls($message, array $post_info)
	{
		$forum_id	= (int) $post_info['forum_id'];
		$topic_id	= (int) $post_info['topic_id'];
		$post_id	= (int) $post_info['post_id'];

		$forum_data = $this->functions->forum_data((int) $forum_id);

		// If the dice extension is not enabled for this forum, we return.
		if (!$forum_data['dice_enabled'] || !$this->auth->acl_get('f_dice_view', $forum_id))
		{
			return $message;
		}

		$rolls = [];

		// Get the dice skin
		$skin_data = $this->functions->get_dice_skin_data($forum_data['dice_skin_override'], $forum_data['dice_f_skin']);

		// Get entities for the post list
		$entities = $this->operator->get_rolls_for_topic($forum_id, $topic_id, [$post_id]);

		/** @var \phpbbstudio\dice\entity\roll $entity */
		foreach ($entities as $entity)
		{
			// Add the roll data to the topic data
			$rolls[$entity->get_id()] = $this->operator->get_roll_data_for_display($entity, $skin_data);
		}

		if ($this->auth->acl_get('f_dice_view', $forum_id) && $rolls)
		{
			$message = $this->replace_rolls($message, $rolls, $skin_data, $post_info, false);

			// Lets do the quotes!
			$message = preg_replace_callback(
				'/<blockquote[^>]*>(.+?)<\/blockquote>/s',
				function ($match) use (&$rolls, $skin_data, $post_info) {
					return $this->replace_rolls($match[0], $rolls, $skin_data, $post_info, true);
				},
				$message
			);

			$outline = $this->display_dice_rolls_not_inline($rolls, $post_id);

			$this->template->assign_vars([
				'DICE_ROLLS_OUTLINE'	=> $outline,

				'S_DICE_MCP_DISPLAY'	=> true,
			]);
		}

		return $message;
	}

	/**
	 * Replace dice rolls in a message.
	 *
	 * @param  string	$message		The message
	 * @param  array	$rolls			The dice rolls
	 * @param  array	$skin			The dice skin data
	 * @param  array	$row			The post row data
	 * @param  bool		$quote			Whether we're replacing rolls inside a quote
	 * @param  bool		$bbcode			Whether we're looking for the BBCode or the HTML replacement
	 * @return string
	 * @access protected
	 */
	protected function replace_rolls($message, array &$rolls, array $skin, array $row, $quote = false, $bbcode = false)
	{
		$regex = $bbcode ? '/\[roll=([0-9]+)\].+?\[\/roll\]/s' : '/<span class="phpbbstudio-dice" data-dice-id="([0-9]+)">.+?<\/span>/s';

		return preg_replace_callback(
			$regex,
			function($match) use (&$rolls, $skin, $row, $quote)
			{
				// Capture group 1 is the roll identifier
				$roll_id = (int) $match[1];
				$roll = isset($rolls[$roll_id]) ? $rolls[$roll_id] : [];

				// If the roll exists
				if ($roll)
				{
					// It belongs to this post or is in a quote
					if (($roll['post'] == $row['post_id']) || $quote)
					{
						// Set it as displayed in-line
						$rolls[$roll_id]['inline'] = true;

						// Assign the roll variables
						return $this->operator->assign_roll_vars($roll);
					}
					else
					{
						return $match[0];
					}
				}
				else if ($quote)
				{
					// Lets check if we tried querying this roll before and could not find it.
					$not_found = isset($rolls['not_found']) ? $rolls['not_found'] : [];
					if (in_array($roll_id, $not_found))
					{
						return $match[0];
					}

					try
					{
						// Try to load the roll from the database
						$entity = $this->operator->get_entity()->load($roll_id);
					}
					catch (\phpbbstudio\dice\exception\out_of_bounds $e)
					{
						// The roll was not found, so lets add it to the 'not_found' array.
						$rolls['not_found'][] = $roll_id;

						// The roll does not exist, so return the string
						return $match[0];
					}

					$roll = $this->operator->get_roll_data_for_display($entity, $skin);

					// Add the roll to the rolls data
					$rolls[$roll_id] = $roll;

					return $this->operator->assign_roll_vars($roll);
				}
				else
				{
					// Roll was not found, return the entire string
					return $match[0];
				}
			},
			$message
		);
	}

	/**
	 * Display any not in-line dice rolls.
	 *
	 * @param  array		$rolls			The dice rolls
	 * @param  int 			$post_id		The post identifier
	 * @return array						Array of the not in-line displayed dice rolls
	 * @access protected
	 */
	protected function display_dice_rolls_not_inline(array $rolls, $post_id)
	{
		// Enforce data type
		$post_id = (int) $post_id;

		// Set up collection array
		$outline = [];

		// Make sure we are not iterating over not found rolls
		unset($rolls['not_found']);

		foreach ($rolls as $roll_id => $roll)
		{
			// If the roll is already displayed inline or,
			// if the the roll does not belong to this post:
			if ($roll['inline'] || $roll['post'] !== $post_id)
			{
				// we continue..
				continue;
			}

			// Else, lets add it to the outline rolls array
			$outline[] = $this->operator->assign_roll_vars($roll);
		}

		return $outline;
	}
}
