<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\core;

/**
 * phpBB Studio's Dice Common functions.
 */
class functions_common
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\filesystem\filesystem */
	protected $filesystem;

	/** @var \phpbbstudio\dice\core\functions_finder */
	protected $finder;

	/** @var \phpbb\language\language */
	protected $lang;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\user */
	protected $user;

	/** @var string Forums table */
	protected $forums_table;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var array Array of allowed image extensions */
	protected $image_extensions;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\auth\auth							$auth			Authentication object
	 * @param  \phpbb\config\config						$config			Configuration object
	 * @param  \phpbb\config\db_text					$config_text	Configuration database object
	 * @param  \phpbb\db\driver\driver_interface		$db				Database object
	 * @param  \phpbb\filesystem\filesystem				$filesystem		Filesystem object
	 * @param  \phpbbstudio\dice\core\functions_finder	$finder			Dice finder
	 * @param  \phpbb\language\language					$lang			Language object
	 * @param  \phpbb\path_helper						$path_helper	Path helper
	 * @param  \phpbb\user								$user			User object
	 * @param  string									$forums_table	Forums table
	 * @param  string									$root_path		phpBB root path
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\config\db_text $config_text,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\filesystem\filesystem $filesystem,
		functions_finder $finder,
		\phpbb\language\language $lang,
		\phpbb\path_helper $path_helper,
		\phpbb\user $user,
		$forums_table,
		$root_path
	)
	{
		$this->auth			= $auth;
		$this->config		= $config;
		$this->config_text	= $config_text;
		$this->db			= $db;
		$this->filesystem	= $filesystem;
		$this->finder		= $finder;
		$this->lang			= $lang;
		$this->path_helper	= $path_helper;
		$this->user			= $user;
		$this->forums_table	= $forums_table;
		$this->root_path	= $root_path;

		$this->image_extensions = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'svg'];
	}

	/**
	 * Check if there is a dice limit for this forum and user combination.
	 *
	 * @param  int		$forum_id		The forum identifier
	 * @return bool						Whether or not there is a limit
	 * @access public
	 */
	public function dice_limit($forum_id)
	{
		return ($this->config['dice_max_rolls'] && !$this->auth->acl_get('f_dice_no_limit', (int) $forum_id));
	}

	/**
	 * Check if the dice limit has been reached for this forum, user and count combination.
	 *
	 * @param  int		$count			The roll count
	 * @param  int		$forum_id		The forum identifier
	 * @return bool						Whether or not the limit has been reached
	 * @access public
	 */
	public function dice_limit_reached($count, $forum_id)
	{
		return (($count >= $this->config['dice_max_rolls']) && $this->dice_limit($forum_id));
	}

	/**
	 * Compare two user identifiers against each other. Used to determine if this user is the author of a post.
	 *
	 * @param  int		$user_id		User identifier
	 * @return bool						Whether or not the identifiers are identical
	 * @access public
	 */
	public function dice_author($user_id)
	{
		return ((int) $this->user->data['user_id'] === (int) $user_id);
	}

	/**
	 * Check if this user can add a dice in this forum.
	 *
	 * @param  int		$forum_id		Forum identifier
	 * @param  int		$user_id		User identifier of the poster
	 * @return bool						Whether or not this user can add a dice in this forum
	 * @access public
	 */
	public function dice_auth_add($forum_id, $user_id)
	{
		return ($this->auth->acl_get('f_mod_dice_add', (int) $forum_id) || ($this->auth->acl_get('f_dice_roll', (int) $forum_id) && $this->dice_author($user_id)));
	}

	/**
	 * Check if this user can delete a dice in this forum.
	 *
	 * @param  int		$forum_id		Forum identifier
	 * @param  int		$user_id		User identifier of the poster
	 * @return bool						Whether or not this user can delete a dice in this forum
	 * @access public
	 */
	public function dice_auth_delete($forum_id, $user_id)
	{
		return ($this->auth->acl_get('f_mod_dice_delete', (int) $forum_id) || ($this->auth->acl_get('f_dice_delete', (int) $forum_id) && $this->dice_author($user_id)));
	}

	/**
	 * Check if this user can edit a dice in this forum.
	 *
	 * @param  int		$forum_id		Forum identifier
	 * @param  int		$user_id		User identifier of the poster
	 * @return bool						Whether or not this user can edit a dice in this forum
	 * @access public
	 */
	public function dice_auth_edit($forum_id, $user_id)
	{
		return ($this->auth->acl_get('f_mod_dice_edit', (int) $forum_id) || ($this->auth->acl_get('f_dice_edit', (int) $forum_id) && $this->dice_author($user_id)));
	}

	/**
	 * Get a forum's dice settings.
	 *
	 * @param  int		$forum_id		The forum identifier
	 * @return mixed					The forum's dice settings or false if no forum id was provided
	 * @access public
	 */
	public function forum_data($forum_id)
	{
		if (empty($forum_id))
		{
			return false;
		}

		$sql = 'SELECT dice_enabled, dice_skin_override, dice_f_skin FROM ' . $this->forums_table . ' WHERE forum_id = ' . (int) $forum_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row;
	}

	/**
	 * Check if the Dice extension is enabled for a specific forum.
	 *
	 * @param  int		$forum_id		The forum identifier
	 * @return bool						Whether or not the extension is enabled for this forum
	 * @access public
	 */
	public function forum_enabled($forum_id)
	{
		if (empty($forum_id))
		{
			return false;
		}

		$sql = 'SELECT dice_enabled FROM ' . $this->forums_table . ' WHERE forum_id = ' . (int) $forum_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$s_enabled = (bool) $this->db->sql_fetchfield('dice_enabled');
		$this->db->sql_freeresult($result);

		return $s_enabled;
	}

	/**
	 * Check if the skin override is enabled for a specific forum.
	 *
	 * @param  int		$forum_id		The forum identifier
	 * @return bool						Whether or not the skin override is enabled for this forum
	 * @access public
	 */
	public function forum_skin_override($forum_id)
	{
		if (empty($forum_id))
		{
			return false;
		}

		$sql = 'SELECT dice_skin_override FROM ' . $this->forums_table . ' WHERE forum_id = ' . (int) $forum_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$s_override = (bool) $this->db->sql_fetchfield('dice_skin_override');
		$this->db->sql_freeresult($result);

		return $s_override;
	}

	/**
	 * Strip emojis from a string
	 *
	 * @param string		$string
	 * @return string
	 */
	public function dice_strip_emojis($string)
	{
		return preg_replace('/[\x{10000}-\x{10FFFF}]/u', "", $string);
	}

	/**
	 * Get the installed dice skins from the database.
	 *
	 * @param  bool		$include_text		Whether or not the "text" option should be included
	 * @return array						Array of installed dice skins
	 * @access public
	 */
	public function get_dice_skins($include_text = false)
	{
		$skins = json_decode($this->config_text->get('dice_skins'), true);

		if ($include_text)
		{
			array_unshift($skins, $this->lang->lang('DICE_TEXT'));
		}

		return (array) $skins;
	}

	/**
	 * Set the installed dice skins in the database.
	 *
	 * @param  array	$skins				Array of installed dice skins
	 * @return void
	 * @access public
	 */
	public function set_dice_skins(array $skins)
	{
		// Enforce data type
		$skins = (array) $skins;

		$this->config_text->set('dice_skins', json_encode($skins));
	}

	/**
	 * Get the installed dice sides from the database.
	 *
	 * @return array						Array of installed dice sides
	 * @access public
	 */
	public function get_dice_sides()
	{
		return (array) json_decode($this->config_text->get('dice_sides'), true);
	}

	/**
	 * Set the installed dice sides in the database.
	 *
	 * @param  array	$sides				Array of installed dice sides
	 * @return void
	 * @access public
	 */
	public function set_dice_sides(array $sides)
	{
		// Enforce data type
		$sides = (array) $sides;

		$this->config_text->set('dice_sides', json_encode($sides));
	}

	/**
	 * Get the correctly formatted dice skins directory.
	 *
	 * @return string						Correctly formatted dice skins directory
	 * @access public
	 */
	public function get_dice_skins_dir()
	{
		return $this->make_dice_dir($this->config['dice_skins_dir']);
	}

	/**
	 * Correctly format a given directory, prefixed with phpBB's root path and a trailing slash.
	 *
	 * @param  string	$directory			The directory to correctly format
	 * @return string						The correctly formatted directory
	 * @access public
	 */
	public function make_dice_dir($directory)
	{
		return (string) $this->root_path . $directory . '/';
	}

	/**
	 * Check if the dice skins directory exists and is readable.
	 *
	 * @return bool							Whether or not the dice skins directory exists and is readable
	 * @access public
	 */
	public function check_dice_skins_dir()
	{
		return $this->check_dice_dir($this->get_dice_skins_dir());
	}

	/**
	 * Check if a directory or file exists.
	 *
	 * @param  string	$directory			The directory to check for existance and readability
	 * @return bool							Whether or not the directory exists and is readable
	 * @access public
	 */
	public function check_dice_dir($directory)
	{
		return ($this->filesystem->exists($directory) && $this->filesystem->is_readable($directory));
	}

	/**
	 * Update the web root path for a give source.
	 *
	 * @param  string	$src				The source string to update.
	 * @return string						The updated source string.
	 * @access public
	 */
	public function update_dice_img_path($src)
	{
		return $this->path_helper->update_web_root_path($src);
	}

	/**
	 * Find the available dice skins in the dice skins directory.
	 *
	 * @return array						Array of available dice skins
	 * @access public
	 */
	public function available_dice_skins()
	{
		return $this->finder->find_dice_skins($this->get_dice_skins_dir());
	}

	/**
	 * Validate dice skins, check if all images for all installed sides are present.
	 *
	 * @param  string	$skin				The dice skin to validate
	 * @param  array	$sides				Array of installed dice sides
	 * @return bool							Whether or not this dice skin has all images for all installed dice sides
	 * @access public
	 */
	public function validate_dice_skin($skin, array $sides)
	{
		$valid = true;

		$images = $this->finder->find_dice_images($this->get_dice_skins_dir(), $skin, $this->get_image_extensions());

		foreach ($sides as $side)
		{
			for ($i = 1; $i <= $side; $i++)
			{
				$image_name = $this->get_dice_image_notation($side, $i);

				$valid = !in_array($image_name, $images) ? false : $valid;
			}
		}

		return (bool) $valid;
	}

	/**
	 * Get the dice skin image notation.
	 *
	 * @param  int		$sides			The dice sides
	 * @param  int		$roll			The dice roll outcome
	 * @param  string	$ext			The dice skin image extension
	 * @return string					The dice skin image notation
	 * @access public
	 */
	public function get_dice_image_notation($sides, $roll, $ext = '')
	{
		return 'd' . (int) $sides . '_' . (int) $roll . ($ext ? '.' . $ext : '');
	}

	/**
	 * Get the allowed image extensions.
	 *
	 * @return array						Array of allowed image extensions
	 * @access public
	 */
	public function get_image_extensions()
	{
		return (array) $this->image_extensions;
	}

	/**
	 * Excerpts the images extension within a folder previously checked.
	 *
	 * @param  string	$skin			The skin's folder name
	 * @return string					The images extension
	 * @access public
	 */
	public function find_image_extension($skin)
	{
		/**
		 * Find all the files in the skin directory that start with
		 * a "d", as dice image file names are: d0_0.ext
		 */
		$files = glob($this->get_dice_skins_dir() . $skin . '/d*.*');

		if ($files)
		{
			$extension = false;
			$count = count($files);

			// Loop over the files found
			for ($i = 0; $i < $count; $i++)
			{
				// Get the extension for this file
				$extension = pathinfo($files[$i], PATHINFO_EXTENSION);

				// If the extension is an allowed image extension, break out the for loop.
				if (in_array($extension, $this->image_extensions))
				{
					break;
				}
			}

			return $extension;
		}

		return false;
	}

	/**
	 * Get the dice skin data for display.
	 *
	 * @param  bool		$override			Whether or not the user's choice should be overridden
	 * @param  string	$forum_skin			The dice skin set in the Forum Settings
	 * @return array						Array with the dice skin data
	 * @access public
	 */
	public function get_dice_skin_data($override, $forum_skin)
	{
		// Get all 'installed' dice skins
		$skins = $this->get_dice_skins(true);

		// Get the user defined style, if not overridden by the forum settings
		$skin = ($this->auth->acl_get('u_dice_skin') || !$override) ? $this->user->data['dice_u_skin'] : $forum_skin;

		// Make sure the user defined style is in the 'installed' dice skins
		$skin = in_array($skin, $skins) ? $skin : 'text';

		// Get the image extension for this dice skin
		$ext = $skin !== 'text' ? $this->find_image_extension($skin) : '';

		return (array) [
			'name'		=> $skin,
			'dir'		=> $this->get_dice_skins_dir(),
			'ext'		=> $ext,
		];
	}

	/**
	 * Clean an array, used for the installed dice skins and dice sides.
	 *
	 * @param  array	$array				The array to clean
	 * @return array						A cleaned array
	 * @access public
	 */
	public function clean_dice_array(array $array)
	{
		// No empty values
		$array = array_filter($array);

		// No duplicate values
		$array = array_unique($array);

		// Sort ascending
		sort($array);

		return (array) $array;
	}

	/**
	 * Build an options string for a HTML <select> field.
	 *
	 * @param  array	$array				The array to build the options from
	 * @param  mixed	$select				The option that should be selected
	 * @param  bool		$no_keys			Whether or not to use the array keys as <option value="">
	 * @return string						An string of all options for a select field
	 */
	public function build_dice_select(array $array, $select, $no_keys)
	{
		$options = '';

		foreach ($array as $key => $option)
		{
			$value = $no_keys ? $option : $key;
			$selected = $select == $value ? '" selected="selected' : '';

			$options .= '<option value="' . $value . $selected .'">' . $option . '</option>';
		}

		return (string) $options;
	}

	/**
	 * The dice link locations and their BIT values
	 *
	 * @return array
	 * @access protected
	 */
	protected function dice_link_locations()
	{
		return [
			1	=> 'navbar_header_quick_links_before',
			2	=> 'navbar_header_quick_links_after',
			4	=> 'overall_header_navigation_prepend',
			8	=> 'overall_header_navigation_append',
			16	=> 'navbar_header_user_profile_append',
			32	=> 'overall_footer_breadcrumb_append',
			64	=> 'overall_footer_timezone_before',
			128	=> 'overall_footer_timezone_after',
			256	=> 'overall_footer_teamlink_before',
			512	=> 'overall_footer_teamlink_after',
		];
	}

	/**
	 * Get dice link locations and their status.
	 *
	 * @return array					Array with the link locations and their status
	 * @access public
	 */
	public function get_dice_link_locations()
	{
		$links = [];
		$flags = $this->config['dice_link_locations'];
		$locations = $this->dice_link_locations();

		foreach($locations as $flag => $location)
		{
			$links[] = [
				'name'		=> $location,
				'status'	=> ($flags & $flag) ? true : false,
			];
		}

		return $links;
	}

	/**
	 * Set dice link locations and their status.
	 *
	 * @param  array	$links			Array with the link locations and their status
	 * @return void
	 * @access public
	 */
	public function set_dice_link_locations(array $links)
	{
		$flags = 0;
		$locations = $this->dice_link_locations();
		$flipped = array_flip($locations);

		foreach ($links as $link => $status)
		{
			$flags += ($status) ? $flipped[$link] : 0;
		}

		$this->config->set('dice_link_locations', (int) $flags);
	}
}
