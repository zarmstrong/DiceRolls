<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\operator;

/**
 * Interface for our roll operator.
 *
 * This describes all of the methods we'll have for working with a set of rolls.
 */
interface roll_interface
{
	/**
	 * Get a roll entity.
	 *
	 * @return object	\phpbbstudio\dice\entity\roll
	 * @access public
	 */
	public function get_entity();

	/**
	 * Create roll entities from a rowset.
	 *
	 * @param  array	$rowset		A rowset of fetched from the rolls table
	 * @return array				Array of roll entities
	 * @access public
	 */
	public function get_entities(array $rowset);

	/**
	 * Delete roll entity|entities.
	 *
	 * @param  mixed	$roll_ids	Roll identifier(s) to delete (int|array)
	 * @return bool					Whether any rows were deleted
	 * @access public
	 */
	public function delete($roll_ids);

	/**
	 * Get the author of the post this roll belongs to.
	 *
	 * @param  int		$roll_id	Roll identifier
	 * @return int					User identifier of the poster
	 * @access public
	 */
	public function get_author($roll_id);

	/**
	 * Get roll entities for display.
	 *
	 * @param  int		$forum_id	Forum identifier
	 * @param  int		$topic_id	Topic identifier
	 * @param  array	$post_list	Array with post identifiers
	 * @return array				Array of roll entities
	 * @access public
	 */
	public function get_rolls_for_topic($forum_id, $topic_id, array $post_list);

	/**
	 * Get roll entities for posting, depending on the identifiers.
	 *
	 * @param  int		$forum_id		The forum identifier
	 * @param  int		$topic_id		The topic identifier
	 * @param  int		$post_id		The post identifier
	 * @return array
	 * @access public
	 */
	public function get_rolls_for_posting($forum_id, $topic_id, $post_id);

	/**
	 * Update identifiers after a post has been submitted.
	 *
	 * @param  string		$mode			The post mode
	 * @param  int			$forum_id		The forum identifier
	 * @param  int			$topic_id		The topic identifier
	 * @param  int			$post_id		The post identifier
	 * @return void
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_rolls_identifiers($mode, $forum_id, $topic_id, $post_id);

	/**
	 * Assign roll entities' variables to the template.
	 *
	 * @param  array		$entities		Array of roll entities
	 * @param  string		$block			The template block name
	 * @return void
	 * @access public
	 */
	public function assign_block_vars(array $entities, $block = 'dice_rolls');

	/**
	 * Get roll data used for editing.
	 *
	 * @param  \phpbbstudio\dice\entity\roll	$entity		The roll entity
	 * @return array										Array with the roll data
	 * @access public
	 */
	public function get_roll_data_for_edit($entity);

	/**
	 * Get roll data used for display.
	 *
	 * @param  \phpbbstudio\dice\entity\roll	$entity		The roll entity
	 * @param  array							$skin		Dice skin data
	 * @return array										Array with the roll data
	 * @access public
	 */
	public function get_roll_data_for_display($entity, array $skin);

	/**
	 * Assign roll variables to the template.
	 *
	 * @param  array							$roll		Array with the roll data
	 * @return string										String of the compiled roll
	 * @access public
	 */
	public function assign_roll_vars(array $roll);
}
