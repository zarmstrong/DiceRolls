<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\entity;

/**
 * Interface for a roll.
 *
 * This describes all of the methods we'll have for a single roll.
 *
 * The basic usage of a roll entity should look like one of three ways:
 * 1. Creation
 * 		-> set_notation()			Set the notation, will also set_dices()
 * 		-> roll()					Roll the dices from the notation, will also set_rolls(), set_total(), set_output()
 *		-> insert()					Inserts the roll data in the database [roll SHOULD NOT exist yet]
 *
 * 2. Modification
 * 		-> load()					Load the roll data from the database
 * 		-> set_...()				Use a set_...() [setter] to set the roll's data
 * 		-> save()					Update the roll data in the database [roll SHOULD exist already]
 *
 * 3. Display
 * 		-> import()					Import the roll data, retrieved from the database elsewhere
 * 		-> get_...()				Use a get_...() [getter] to get the roll's data
 */
interface roll_interface
{
	/**
	 * Load data for a roll.
	 *
	 * @param  int					$id			Roll identifier
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function load($id);

	/**
	 * Import data for a roll.
	 *
	 * Used when the data is already loaded externally.
	 * Any existing data on this roll is over-written.
	 *
	 * @param  array				$data		Data for this roll
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 */
	public function import(array $data);

	/**
	 * Insert the roll data for the first time.
	 *
	 * Will give an error if the roll was already inserted, call save() instead.
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function insert();

	/**
	 * Save the roll data to the database
	 *
	 * This must be called before closing or any changes will not be saved!
	 * If adding a roll (saving for the first time), you must call insert() or an error will be given.
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function save();

	/**
	 * Roll the dice for the set notation.
	 *
	 * This will go over all the dice retrieved from this notation with get_dices()
	 * @see roll_interface::get_dices()
	 * It will then create all the rolls for those dices and set them through set_rolls()
	 * @see roll_interface::set_rolls()
	 * After all the rolls have been calculated, the total of all rolls can be set in set_total()
	 * @see roll_interface::set_total()
	 * And to finish it all off, we can create the output string in set_output()
	 * @see roll_interface::set_output()
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\unexpected_value
	 */
	public function roll();

	/**
	 * Get the roll identifier.
	 *
	 * @return int								Roll identifier
	 * @access public
	 */
	public function get_id();

	/**
	 * Get the forum identifier.
	 *
	 * @return int								Forum identifier
	 * @access public
	 */
	public function get_forum();

	/**
	 * Set the forum identifier.
	 *
	 * @param  int					$id			Forum identifier
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_forum($id);

	/**
	 * Get the topic identifier.
	 *
	 * @return int								Topic identifier
	 * @access public
	 */
	public function get_topic();

	/**
	 * Set the topic identifier.
	 *
	 * @param  int					$id			Topic identifier
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_topic($id);

	/**
	 * Get the post identifier.
	 *
	 * @return int								Post identifier
	 * @access public
	 */
	public function get_post();

	/**
	 * Set the post identifier.
	 *
	 * @param  int					$id			Post identifier
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_post($id);

	/**
	 * Get the user identifier.
	 *
	 * @return int								User identifier
	 * @access public
	 */
	public function get_user();

	/**
	 * Set the user identifier.
	 *
	 * @param  int					$id			User identifier
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\base
	 */
	public function set_user($id);

	/**
	 * Get the roll notation.
	 *
	 * @return string							Roll notation
	 * @access public
	 */
	public function get_notation();

	/**
	 * Set the roll notation.
	 *
	 * This will also call set_dices(), which retrieves all different dices from the notation
	 * @see roll_interface::set_dices()
	 *
	 * @param  string				$notation	Roll notation
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\unexpected_value
	 */
	public function set_notation($notation);

	/**
	 * Get the dices from the roll notation.
	 *
	 * @return array							Dices from the roll notation
	 * @access public
	 */
	public function get_dices();

	/**
	 * Set the dices from the roll notation.
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\unexpected_value
	 */
	public function set_dices();

	/**
	 * Get the rolls from the dice.
	 *
	 * @return array							Rolls from the dices
	 * @access public
	 */
	public function get_rolls();

	/**
	 * Set the rolls from the dice.
	 *
	 * @param  array				$rolls		Rolls from the dices
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 */
	public function set_rolls(array $rolls);

	/**
	 * Get the roll data for display.
	 *
	 * @param  string				$skin		The dice skin to use
	 * @param  string				$dir		The dice skin directory
	 * @param  string				$ext		The dice skin image extension
	 * @return array							Roll data array to be used for display
	 * @access public
	 */
	public function get_display($skin, $dir, $ext = '');

	/**
	 * Get the roll output.
	 *
	 * @return string							Roll output
	 * @access public
	 */
	public function get_output();

	/**
	 * Set the roll output.
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 */
	public function set_output();

	/**
	 * Get the total for the roll notation.
	 *
	 * @return int								Total for the roll notation
	 * @access public
	 */
	public function get_total();

	/**
	 * Set the total for the roll notation.
	 *
	 * This will also call set_successes() for the pool dice that met their condition
	 * and set_is_pool() to indicate that the notation contains pool dice
	 * @see roll_interface::set_successes()
	 * @see roll_interface::set_is_pool()
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_total();

	/**
	 * Set the successes for the rolls.
	 *
	 * @return int								Successes for the rolls.
	 * @access public
	 */
	public function get_successes();

	/**
	 * Set the successes for the rolls.
	 *
	 * @param  $successes			$successes	Successes for the rolls.
	 * @return roll_interface		$this		This object for chaining calls
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_successes($successes);

	/**
	 * Get the is_pool status for the notation.
	 *
	 * @return bool								Whether or not this notation has a dice pool
	 * @access public
	 */
	public function get_is_pool();

	/**
	 * Set the is_pool status for the notation
	 *
	 * @param  bool					$is_pool	Whether or not this notation has a dice pool
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 */
	public function set_is_pool($is_pool);

	/**
	 * Get the roll time.
	 *
	 * @return int								Roll time (UNIX timestamp)
	 * @access public
	 */
	public function get_time();

	/**
	 * Set the roll time.
	 *
	 * @param  int					$time		Roll time (UNIX timestamp)
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_time($time);

	/**
	 * Get the user identifier for the last edit.
	 *
	 * @return int								User identifier
	 * @access public
	 */
	public function get_edit_user();

	/**
	 * Set the user identifier for the last edit.
	 *
	 * @param  int					$id			User identifier
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_edit_user($id);

	/**
	 * Get the roll edit time.
	 *
	 * @return int								Roll edit time (UNIX timestamp)
	 * @access public
	 */
	public function get_edit_time();

	/**
	 * Set the roll edit time.
	 *
	 * @param  int					$time		Roll edit time (UNIX timestamp)
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_edit_time($time);

	/**
	 * Get the roll edit count.
	 *
	 * @return int								Roll edit count
	 * @access public
	 */
	public function get_edit_count();

	/**
	 * Set the roll edit count.
	 *
	 * @param  int					$count		Roll edit count
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function set_edit_count($count);

	/**
	 * Increment the roll edit count by one.
	 *
	 * @return roll_interface		$this		This object for chaining calls
	 * @access public
	 * @throws \phpbbstudio\dice\exception\out_of_bounds
	 */
	public function increment_edit_count();
}
