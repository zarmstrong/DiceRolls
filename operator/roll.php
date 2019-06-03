<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\operator;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for roll entities.
 */
class roll implements roll_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\filesystem\filesystem */
	protected $filesystem;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string Posts table */
	protected $posts_table;

	/** @var string Dice rolls table */
	protected $rolls_table;

	/** @var string phpBB root path */
	protected $root_path;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\config\config						$config			Configuration object
	 * @param  \phpbb\config\db_text					$config_text	Text configuration object
	 * @param  ContainerInterface						$container		Container injection Service
	 * @param  \phpbb\db\driver\driver_interface		$db				Database object
	 * @param  \phpbb\filesystem\filesystem				$filesystem		Filesystem object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions		Dice common functions
	 * @param  \phpbb\controller\helper					$helper			Controller helper object
	 * @param  \phpbb\template\template					$template		Template object
	 * @param  \phpbb\user								$user			User object
	 * @param  string									$posts_table	Posts table
	 * @param  string									$rolls_table	Dice rolls table
	 * @param  string									$root_path		phpBB root path
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\config\db_text $config_text,
		ContainerInterface $container,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\filesystem\filesystem $filesystem,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\controller\helper $helper,
		\phpbb\template\template $template,
		\phpbb\user $user,
		$posts_table,
		$rolls_table,
		$root_path
	)
	{
		$this->config		= $config;
		$this->config_text	= $config_text;
		$this->container	= $container;
		$this->db			= $db;
		$this->filesystem	= $filesystem;
		$this->functions	= $functions;
		$this->helper		= $helper;
		$this->template		= $template;
		$this->user			= $user;
		$this->posts_table	= $posts_table;
		$this->rolls_table	= $rolls_table;
		$this->root_path	= $root_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_entity()
	{
		return $this->container->get('phpbbstudio.dice.entity.roll');
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_entities(array $rowset)
	{
		// Set up an array for entities collection.
		$entities = [];

		foreach ($rowset as $row)
		{
			// Get an entity, import the row data and add it to the entities array
			$entities[] = $this->get_entity()->import($row);
		}

		return $entities;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($roll_ids)
	{
		// No roll identifiers were provided
		if (empty($roll_ids))
		{
			return false;
		}

		$sql_where = is_array($roll_ids) ? $this->db->sql_in_set('roll_id', $roll_ids) : 'roll_id = ' . (int) $roll_ids;

		$sql = 'DELETE FROM ' . $this->rolls_table . ' WHERE ' . $sql_where;
		$this->db->sql_query($sql);

		return (bool) $this->db->sql_affectedrows();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_author($roll_id)
	{
		$sql = 'SELECT p.poster_id
				FROM ' . $this->rolls_table . ' r
				LEFT JOIN ' . $this->posts_table . ' p
					ON p.post_id = r.post_id
				WHERE r.roll_id = ' . (int) $roll_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$poster_id = $this->db->sql_fetchfield('poster_id');
		$this->db->sql_freeresult($result);

		return (int) $poster_id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_rolls_for_topic($forum_id, $topic_id, array $post_list)
	{
		$sql = 'SELECT *
				FROM ' . $this->rolls_table . '
				WHERE forum_id = ' . (int) $forum_id . '
					AND topic_id = ' . (int) $topic_id . '
					AND ' . $this->db->sql_in_set('post_id', $post_list);
		$result = $this->db->sql_query($sql);
		$rowset = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $this->get_entities($rowset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_rolls_for_posting($forum_id, $topic_id, $post_id)
	{
		$sql = 'SELECT *
				FROM ' . $this->rolls_table . '
				WHERE forum_id = ' . (int) $forum_id . '
					AND topic_id = ' . (int) $topic_id . '
					AND post_id = ' . (int) $post_id;
		$sql .= (empty($topic_id) && empty($post_id)) ? ' AND user_id = ' . (int) $this->user->data['user_id'] : '';
		$result = $this->db->sql_query($sql);
		$rowset = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $this->get_entities($rowset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_rolls_identifiers($mode, $forum_id, $topic_id, $post_id)
	{
		// If the mode is 'post', a topic was created
		$s_topic = $mode === 'post';

		// If a topic was created, the rolls currently have no topic identifier
		$sql = 'SELECT *
				FROM ' . $this->rolls_table . '
				WHERE forum_id = ' . (int) $forum_id . '
					AND user_id = ' . (int) $this->user->data['user_id'] . '
					AND topic_id = ' . ($s_topic ? 0 : (int) $topic_id) . '
					AND post_id = 0';
		$result = $this->db->sql_query($sql);
		$rowset = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		// Get entities from rowset
		$entities = $this->get_entities($rowset);

		/** @var \phpbbstudio\dice\entity\roll $entity */
		foreach ($entities as $entity)
		{
			// If a topic was created, set the topic identifier
			if ($s_topic)
			{
				$entity->set_topic($topic_id);
			}

			// Set the post identifier and save the entity
			$entity->set_post($post_id)
					->save();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function assign_block_vars(array $entities, $block = 'dice_rolls')
	{
		/** @var \phpbbstudio\dice\entity\roll $entity */
		foreach ($entities as $entity)
		{
			$this->template->assign_block_vars($block, [
				'FORUM_ID'		=> $entity->get_forum(),
				'TOPIC_ID'		=> $entity->get_topic(),
				'POST_ID'		=> $entity->get_post(),
				'USER_ID'		=> $entity->get_user(),
				'ROLL_ID'		=> $entity->get_id(),
				'ROLL_NOTATION'	=> $entity->get_notation(),
				'ROLL_TIME'		=> $this->user->format_date($entity->get_time()),

				'U_DELETE'		=> $this->helper->route('phpbbstudio_dice_del', ['roll_id' => (int) $entity->get_id()]),
				'U_EDIT'		=> $this->helper->route('phpbbstudio_dice_edit', ['roll_id' => (int) $entity->get_id()]),
			]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_roll_data_for_edit($entity)
	{
		/** @var \phpbbstudio\dice\entity\roll $entity */
		return [
			'id'		=> $entity->get_id(),
			'notation'	=> $entity->get_notation(),
			'time'		=> $this->user->format_date($entity->get_time()),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_roll_data_for_display($entity, array $skin)
	{
		/** @var \phpbbstudio\dice\entity\roll $entity */
		return [
			'notation'	=> $entity->get_notation(),
			'display'	=> $entity->get_display($skin['name'], $skin['dir'], $skin['ext']),
			'output'	=> $entity->get_output(),
			'total'		=> $entity->get_total(),
			'post'		=> $entity->get_post(),
			'inline'	=> false,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function assign_roll_vars(array $roll)
	{
		$this->template->set_filenames([
			'dice'	=> '@phpbbstudio_dice/dice_roll.html',
		]);

		$this->template->assign_vars([
			'NOTATION'			=> $roll['notation'],
			'DISPLAY'			=> $roll['display'],
			'OUTPUT'			=> $roll['output'],
			'TOTAL'				=> $roll['total'],
			'DICE_IMG_HEIGHT'	=> (int) $this->config['dice_skins_img_height'],
			'DICE_IMG_WIDTH'	=> (int) $this->config['dice_skins_img_width'],
		]);

		return $this->template->assign_display('dice');
	}
}
