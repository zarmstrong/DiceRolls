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
 * phpBB Studio's Dice Posting listener.
 */
class posting_listener implements EventSubscriberInterface
{
	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbbstudio\dice\operator\roll */
	protected $operator;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor.
	 *
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Common functions
	 * @param  \phpbb\controller\helper					$helper			Controller helper object
	 * @param  \phpbbstudio\dice\operator\roll			$operator		Roll operator object
	 * @param  \phpbb\request\request					$request		Request object
	 * @param  \phpbb\template\template					$template		Template object
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\controller\helper $helper,
		\phpbbstudio\dice\operator\roll $operator,
		\phpbb\request\request $request,
		\phpbb\template\template $template
	)
	{
		$this->functions	= $functions;
		$this->helper		= $helper;
		$this->operator		= $operator;
		$this->request		= $request;
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
			'core.posting_modify_post_data'				=> 'dice_posting_requests',
			'core.posting_modify_submit_post_before'	=> 'dice_posting_submit',
			'core.submit_post_end'						=> 'dice_posting_update',
			'core.posting_modify_template_vars'			=> 'dice_posting_variables',
		];
	}

	/**
	 * Request dice roll indicator.
	 *
	 * @event  core.posting_modify_post_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_posting_requests($event)
	{
		$event['post_data'] = array_merge($event['post_data'], [
			'dice_indicator'	=> $this->request->is_set_post('dice_indicator'),
		]);
	}

	/**
	 * Copy the dice roll indicator so it is available in dice_posting_update()
	 *
	 * @event  core.posting_modify_submit_post_before
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_posting_submit($event)
	{
		$event['data'] = array_merge($event['data'], [
			'dice_indicator'	=> $event['post_data']['dice_indicator'],
		]);
	}

	/**
	 * Update the roll identifiers after the post has been submitted.
	 *
	 * @event  core.submit_post_end
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function dice_posting_update($event)
	{
		// Grab the event data
		$data = $event['data'];
		$mode = $event['mode'];

		// Grab the identifiers
		$forum_id	= (int) $data['forum_id'];
		$topic_id	= (int) $data['topic_id'];
		$post_id	= (int) $data['post_id'];

		if ($data['dice_indicator'])
		{
			switch ($mode)
			{
				case 'post':
				case 'reply':
				case 'quote':
					// Set identifiers
					$this->operator->set_rolls_identifiers($mode, $forum_id, $topic_id, $post_id);
				break;

				default:
					// All identifiers are already set
				break;
			}
		}
	}

	/**
	 * Assign roll and extension data to the template.
	 *
	 * @event  core.posting_modify_template_vars
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_posting_variables($event)
	{
		// Grab the event data
		$mode		= $event['mode'];
		$post_data	= $event['post_data'];

		// Grab the identifiers
		$forum_id	= (int) $event['forum_id'];
		$topic_id	= (int) $event['topic_id'];
		$post_id	= (int) $event['post_id'];
		$poster_id	= (int) $post_data['poster_id'];

		// If we are quoting a post, we have to reset the post identifier
		$post_id = $mode === 'quote' ? 0 : $post_id;

		// Get roll entities for this combination of identifiers
		$entities = $this->operator->get_rolls_for_posting($forum_id, $topic_id, $post_id);

		// Assign the rolls data to the template
		$this->operator->assign_block_vars($entities);

		// Count the entities
		$count = count($entities);

		/**
		 * @var bool	S_DICE_INDICATOR		Used to determine if a dice roll was added, needed for other events
		 * @var string	U_DICE_ADD				URL to add a dice roll for this post
		 * @var string	U_DICE_DEL				Base URL to delete a dice roll, needed for the AJAX callback
		 * @var string	U_DICE_EDIT				Base URL to edit a dice roll, needed for the AJAX callback
		 */
		$this->template->assign_vars([
			'S_DICE_ENABLED'	=> (bool) $post_data['dice_enabled'],
			'S_DICE_INDICATOR'	=> isset($post_data['dice_indicator']) ? (bool) $post_data['dice_indicator'] : false,
			'S_DICE_LIMIT'		=> (bool) $this->functions->dice_limit_reached($count, $forum_id),

			'S_ROLL_ADD'		=> (bool) $this->functions->dice_auth_add($forum_id, $poster_id),
			'S_ROLL_DELETE'		=> (bool) $this->functions->dice_auth_delete($forum_id, $poster_id),
			'S_ROLL_EDIT'		=> (bool) $this->functions->dice_auth_edit($forum_id, $poster_id),

			'U_DICE_ADD'		=> $this->helper->route('phpbbstudio_dice_add', [
				'forum_id' => $forum_id,
				'topic_id' => $topic_id,
				'post_id' => $post_id,
				'poster_id' => $poster_id,
				'hash' => generate_link_hash('dice_add')
			]),

			'U_DICE_DELETE'		=> $this->helper->route('phpbbstudio_dice_del'),
			'U_DICE_EDIT'		=> $this->helper->route('phpbbstudio_dice_edit'),
		]);
	}
}
