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
 * phpBB Studio's Dice ACP listener.
 */
class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string Dice rolls table */
	protected $rolls_table;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\db\driver\driver_interface		$db				Database object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Dice common functions
	 * @param  \phpbb\request\request					$request		Request object
	 * @param  string									$rolls_table	Dice rolls table
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\request\request $request,
		$rolls_table
	)
	{
		$this->db			= $db;
		$this->functions	= $functions;
		$this->request		= $request;
		$this->rolls_table	= $rolls_table;
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
			'core.delete_user_after'							=> 'dice_delete_user_after',

			// excluded in order to be able to soft delete posts and rolls and keep rolls quoted elsewhere
			//'core.delete_post_after'							=> 'dice_delete_post_after',

			'core.move_posts_after'								=> 'dice_move_posts_after',

			// excluded in order to be able to keep rolls quoted elsewhere
			//'core.delete_topics_before_query'					=> 'dice_add_rolls_table',

			'core.move_topics_before_query'						=> 'dice_add_rolls_table',

			// excluded in order to be able to keep rolls quoted elsewhere
			//'core.delete_forum_content_before_query'			=> 'dice_add_rolls_table',

			// excluded in order to be able to keep rolls quoted elsewhere
			//'core.delete_posts_in_transaction_before'			=> 'dice_add_rolls_table',

			'core.acp_manage_forums_move_content_sql_before'	=> 'dice_add_rolls_table',
			'core.mcp_main_fork_sql_after'						=> 'dice_mcp_main_fork_sql_after',
			'core.acp_manage_forums_request_data'				=> 'dice_acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data'			=> 'dice_acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'				=> 'dice_acp_manage_forums_display_form',
		];
	}

	/**
	 * Perform actions directly after a user has been deleted
	 * and "delete their posts" or "retain their posts" has been selected.
	 *
	 * @event  core.delete_post_after
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_delete_user_after($event)
	{
		$user_ids = $event['user_ids'];

		if (!empty($user_ids))
		{
			if ($event['mode'] === 'remove' || $event['mode'] === 'retain')
			{
				/* Change user_id to anonymous for rolls by this user */
				$sql = 'UPDATE ' . $this->rolls_table . '
						SET user_id = ' . ANONYMOUS . '
						WHERE ' . $this->db->sql_in_set('user_id', $user_ids);
				$this->db->sql_query($sql);
			}
		}
	}

	/**
	 * Performing actions directly after a post or topic has been deleted.
	 * On hitting the X button of a post in view topic.
	 *
	 * @event  core.delete_post_after
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_delete_post_after($event)
	{
		$sql = 'DELETE FROM ' . $this->rolls_table . ' WHERE post_id = ' . (int) $event['post_id'];
		$this->db->sql_query($sql);
	}

	/**
	 * Perform actions after the posts have been moved
	 *
	 * @event  core.move_posts_after
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_move_posts_after($event)
	{
		$forum_row	= $event['forum_row'];
		$post_ids	= $event['post_ids'];
		$topic_id	= $event['topic_id'];

		$sql = 'UPDATE ' . $this->rolls_table . '
				SET forum_id = ' . (int) $forum_row['forum_id'] . ", 
					topic_id = " . (int) $topic_id . "
				WHERE " . $this->db->sql_in_set('post_id', $post_ids);
		$this->db->sql_query($sql);
	}

	/**
	 * Shared function which adds our rolls table to an array of tables.
	 *
	 * @event  core.delete_topics_before_query					On delete a topic MCP/Quicktools
	 * @event  core.move_topics_before_query					On move a topic MCP/Quicktools moves the rolls too
	 * @event  core.delete_forum_content_before_query			On delete a forum in ACP
	 * @event  core.acp_manage_forums_move_content_sql_before	On move content of a forum in ACP
	 * @event  core.delete_posts_in_transaction_before			On delete posts in MCP
	 * @param  \phpbb\event\data		$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_add_rolls_table($event)
	{
		$table_ary = $event['table_ary'];
		$table_ary[] = $this->rolls_table;
		$event['table_ary'] = $table_ary;
	}

	/**
	 * Forks the topics (rolls) accordingly to the native functionality
	 *
	 * @event  core.mcp_main_fork_sql_after
	 * @param  \phpbb\event\data		$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_mcp_main_fork_sql_after($event)
	{
		$topic_id		= $event['row']['topic_id'];
		$post_id		= $event['row']['post_id'];
		$new_topic_id	= $event['new_topic_id'];
		$to_forum_id	= $event['to_forum_id'];
		$new_post_id	= $event['new_post_id'];

		$sql = 'SELECT *
				FROM ' . $this->rolls_table . '
				WHERE topic_id = ' . (int) $topic_id . '
					AND post_id = ' . (int) $post_id . '
				ORDER BY roll_id ASC';
		$result = $this->db->sql_query($sql);

		$sql_ary = [];

		while ($rolls = $this->db->sql_fetchrow($result))
		{
			$sql_ary[] = [
				'roll_id'			=> (int) $rolls['roll_id'],
				'roll_notation'		=> (string) $rolls['roll_notation'],
				'roll_dices'		=> (string) $rolls['roll_dices'],
				'roll_rolls'		=> (string) $rolls['roll_rolls'],
				'roll_output'		=> (string) $rolls['roll_output'],
				'roll_total'		=> (int) $rolls['roll_total'],
				'roll_successes'	=> (int) $rolls['roll_successes'],
				'roll_is_pool'		=> (int) $rolls['roll_is_pool'],
				'roll_time'			=> (int) $rolls['roll_time'],
				'roll_edit_user'	=> (int) $rolls['roll_edit_user'],
				'roll_edit_time'	=> (int) $rolls['roll_edit_time'],
				'roll_edit_count'	=> (int) $rolls['roll_edit_count'],
				'forum_id'			=> (int) $to_forum_id,
				'topic_id'			=> (int) $new_topic_id,
				'post_id'			=> (int) $new_post_id,
				'user_id'			=> (int) $rolls['user_id'],
			];
		}
		$this->db->sql_freeresult($result);

		if (!empty($sql_ary))
		{
			$this->db->sql_multi_insert($this->rolls_table, $sql_ary);
		}
	}

	/* Here begins the ACP/Forums side of things */

	/**
	 * (Add/update actions) - Submit form.
	 *
	 * @event  core.acp_manage_forums_request_data
	 * @param  \phpbb\event\data		$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_acp_manage_forums_request_data($event)
	{
		$forum_data = $event['forum_data'];

		$forum_data['dice_enabled']			= $this->request->variable('dice_enabled', 0);
		$forum_data['dice_f_skin']			= $this->request->variable('dice_f_skin', '', true);
		$forum_data['dice_skin_override']	= $this->request->variable('dice_skin_override', 0);

		$event['forum_data'] = $forum_data;
	}

	/**
	 * New Forums added (default disabled).
	 *
	 * @event  core.acp_manage_forums_initialise_data
	 * @param  \phpbb\event\data		$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$forum_data = $event['forum_data'];

			$forum_data['dice_enabled']			= false;
			$forum_data['dice_f_skin']			= '';
			$forum_data['dice_skin_override']	= false;

			$event['forum_data'] = $forum_data;
		}
	}

	/**
	 * ACP forums (template data).
	 *
	 * @event  core.acp_manage_forums_display_form
	 * @param  \phpbb\event\data		$event		The event object
	 * @return void
	 * @access public
	 */
	public function dice_acp_manage_forums_display_form($event)
	{
		$template_data = $event['template_data'];

		$skin = $event['forum_data']['dice_f_skin'];
		$skins = $this->functions->get_dice_skins(true);

		$template_data['S_DICE_ENABLED']		= $event['forum_data']['dice_enabled'];
		$template_data['DICE_F_SKIN']			= $this->functions->build_dice_select($skins, $skin, true);
		$template_data['S_DICE_SKIN_OVERRIDE']	= $event['forum_data']['dice_skin_override'];

		$event['template_data'] = $template_data;
	}
}
