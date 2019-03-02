<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\controller;

/**
 * Interface for the main controller.
 */
interface main_interface
{
	/**
	 * Create a roll.
	 *
	 * @param  int		$forum_id				The forum identifier
	 * @param  int		$topic_id				The topic identifier
	 * @param  int		$post_id				The post identifier
	 * @param  int		$poster_id				The poster identifier
	 * @return \phpbb\json_response
	 * @throws \phpbb\exception\http_exception
	 * @access public
	 */
	public function add($forum_id, $topic_id, $post_id, $poster_id);

	/**
	 * Edit a roll.
	 *
	 * @param  int		$roll_id				The roll identifier
	 * @return \phpbb\json_response
	 * @throws \phpbb\exception\http_exception
	 * @access public
	 */
	public function edit($roll_id);

	/**
	 * Delete a roll.
	 *
	 * @param  int		$roll_id		The roll identifier
	 * @return void		\phpbb\json_response
	 * @throws \phpbb\exception\http_exception
	 * @access public
	 */
	public function delete($roll_id);

	/**
	 * Display a dice testing page.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \phpbb\exception\http_exception
	 * @access public
	 */
	public function page();
}