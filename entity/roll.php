<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\entity;

/**
 * Entity for a roll.
 */
class roll implements roll_interface
{
	/**
	 * Data for this entity.
	 *
	 * @var array
	 * 	roll_id
	 * 	roll_notation
	 *	roll_dices
	 *	roll_rolls
	 * 	roll_output
	 *	roll_total
	 *	roll_successes
	 *	roll_is_pool
	 * 	roll_time
	 * 	roll_edit_user
	 * 	roll_edit_time
	 * 	roll_edit_count
	 * 	forum_id
	 * 	topic_id
	 * 	post_id
	 * 	user_id
	 * @access protected
	 */
	protected $data;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\language\language */
	protected $lang;

	/** @var \phpbbstudio\dice\core\functions_regex */
	protected $regex;

	/** @var \phpbbstudio\dice\core\functions_utils */
	protected $utils;

	/** @var string Dice rolls table */
	protected $table;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\config\config						$config		Configuration object
	 * @param  \phpbb\db\driver\driver_interface		$db			Database object
	 * @param  \phpbbstudio\dice\core\functions_common	$functions	Common functions
	 * @param  \phpbb\language\language					$lang		Language object
	 * @param  \phpbbstudio\dice\core\functions_regex	$regex		Regex functions
	 * @param  \phpbbstudio\dice\core\functions_utils	$utils		Utility functions
	 * @param  string									$table		Dice rolls table
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\language\language $lang,
		\phpbbstudio\dice\core\functions_regex $regex,
		\phpbbstudio\dice\core\functions_utils $utils,
		$table
	)
	{
		$this->config		= $config;
		$this->db			= $db;
		$this->functions	= $functions;
		$this->lang			= $lang;
		$this->regex		= $regex;
		$this->utils		= $utils;
		$this->table		= $table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function load($id)
	{
		$sql = 'SELECT *
			FROM ' . $this->table . '
			WHERE roll_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($this->data === false)
		{
			// The roll does not exist
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_ID', 'ROLL_NOT_EXIST']);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function import(array $data)
	{
		$this->data = [];

		foreach ($data as $key => $value)
		{
			$this->data[$key] = $value;
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function insert()
	{
		if (!empty($this->data['roll_id']))
		{
			// The roll already exists
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_ID', 'ROLL_ALREADY_EXIST']);
		}

		// Insert the roll data to the database
		$sql = 'INSERT INTO ' . $this->table . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the roll_id using the id created by the SQL insert
		$this->data['roll_id'] = (int) $this->db->sql_nextid();

		/**
		 * And update the table, as we have a different primary key
		 * That's for forked topics where we can't use the autoincrement value.
		 */
		$sql = 'UPDATE ' . $this->table . ' SET roll_id = ' . (int) $this->data['roll_id'] . ' WHERE roll_num = ' . (int) $this->data['roll_id'];
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save()
	{
		if (empty($this->data['roll_id']))
		{
			// The roll does not exist
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_ID', 'ROLL_NOT_EXIST']);
		}

		/**
		 * Copy the data array, filtering out the roll_id identifier
		 * so we do not attempt to update the row's identity column.
		 */
		$sql_array = array_diff_key($this->data, ['roll_num' => null, 'roll_id' => null]);

		// Update the roll data in the database
		$sql = 'UPDATE ' . $this->table . '
			SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . '
			WHERE roll_id = ' . (int) $this->get_id();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function roll()
	{
		// There are no dices to roll!
		if (!$this->get_dices())
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICES', 'FIELD_MISSING']);
		}

		$notation_rolls = [];

		foreach ($this->get_dices() as $dice)
		{
			$rolls = $re_rolls = [];

			$sides = $dice['sides'];
			$callback = 'default_dice';

			// Ensure the roll quantity is valid
			$dice['qty'] = ($dice['qty'] > 0) ? $dice['qty'] : 1;

			// check for non-numerical dice formats
			if ($dice['fudge'])
			{
				// we have a fudge dice - define the callback to return the `fudge` roll method
				$callback = 'fudge_dice';

				// set the `sides` to the correct value for the fudge type
				$sides = (isset($dice['fudge'][1]) && $this->utils->is_numeric($dice['fudge'][1])) ? intval($dice['fudge'][1]) : 2;
			}
			else if (gettype($dice['sides']) === 'string')
			{
				if ($dice['sides'] === '%'){
					// convert percentile to 100 sided die
					$sides = 100;
				}
			}

			// only continue if the number of sides is valid
			if ($sides)
			{
				// loop through and roll for the quantity
				for ($i = 0; $i < $dice['qty']; $i++)
				{
					// the rolls for the current die (only multiple rolls if exploding)
					$re_rolls = [];

					// count of rolls for this die roll (Only > 1 if exploding)
					$roll_count = 0;

					/** @noinspection PhpUnusedLocalVariableInspection */
					// the total rolled
					$roll_total = 0;

					/** @noinspection PhpUnusedLocalVariableInspection */
					// re-roll index
					$roll_index = 0;

					do
					{
						// the reRolls index to use
						$roll_index = count($re_rolls);

						// get the total rolled on this die
						$roll_total = $this->utils->{$callback}($sides);

						// add the roll to our list
						$re_rolls[$roll_index] = isset($re_rolls[$roll_index]) ? $re_rolls[$roll_index] + $roll_total : $roll_total;

						// subtract 1 from penetrated rolls (only consecutive rolls, after initial roll are subtracted)
						if ($dice['penetrate'] && ($roll_count > 0))
						{
							$re_rolls[$roll_index]--;
						}

						$roll_count++;
					}
					while ($dice['explode'] && $this->utils->is_compare_point($dice['compare_point'], $roll_total));

					$rolls = array_merge($rolls, $re_rolls);
				}
			}

			$notation_rolls[] = $rolls;
		}

		// Set the rolls, total and output
		$this->set_rolls($notation_rolls)
				->set_total()
				->set_output();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_id()
	{
		return isset($this->data['roll_id']) ? (int) $this->data['roll_id'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_forum()
	{
		return isset($this->data['forum_id']) ? (int) $this->data['forum_id'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_forum($id)
	{
		// Enforce data type
		$id = (int) $id;

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB.
		 */
		if ($id < 0 || $id > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_FORUM_ID', 'ROLL_ULINT']);
		}

		// Add the identifier to the data array
		$this->data['forum_id'] = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_topic()
	{
		return isset($this->data['topic_id']) ? (int) $this->data['topic_id'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_topic($id)
	{
		// Enforce data type
		$id = (int) $id;

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB.
		 */
		if ($id < 0 || $id > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_TOPIC_ID', 'ROLL_ULINT']);
		}

		// Add the identifier to the data array
		$this->data['topic_id'] = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_post()
	{
		return isset($this->data['post_id']) ? (int) $this->data['post_id'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_post($id)
	{
		// Enforce data type
		$id = (int) $id;

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB.
		 */
		if ($id < 0 || $id > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_POST_ID', 'ROLL_ULINT']);
		}

		// Add the identifier to the data array
		$this->data['post_id'] = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_user()
	{
		return isset($this->data['user_id']) ? (int) $this->data['user_id'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_user($id)
	{
		// Enforce data type
		$id = (int) $id;

		// User identifier is a required field
		if (empty($id))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_USER_ID', 'FIELD_MISSING']);
		}

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB.
		 */
		if ($id < 0 || $id > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_USER_ID', 'ROLL_ULINT']);
		}

		// Add the identifier to the data array
		$this->data['user_id'] = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_notation()
	{
		return isset($this->data['roll_notation']) ? (string) $this->data['roll_notation'] : '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_notation($notation)
	{
		// Enforce a data type
		$notation = (string) $notation;

		// Notation is a required field
		if ($notation === '')
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_NOTATION', 'FIELD_MISSING']);
		}

		// We limit the notation length to 255 characters
		if (truncate_string($notation, 255) !== $notation)
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_NOTATION', 'TOO_LONG']);
		}

		// Add the notation to the data array
		$this->data['roll_notation'] = $notation;

		// Set the all the dices
		$this->set_dices();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_dices()
	{
		return isset($this->data['roll_dices']) ? (array) json_decode($this->data['roll_dices'], true) : [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_dices()
	{
		if (!$this->get_notation())
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_NOTATION', 'FIELD_MISSING']);
		}

		$dices = [];
		$percentage = $fudge = 0;
		$quantity = $exploding = 0;
		$penetrating = $compounding = 0;

		$pattern = $this->regex->get('notation');
		$notation = $this->get_notation();

		$matches_found = preg_match_all($pattern, $notation, $matches, PREG_SET_ORDER);

		if (!$matches_found)
		{
			throw new \phpbbstudio\dice\exception\unexpected_value((['ROLL_NOTATION', 'ROLL_NO_MATCHES']));
		}

		foreach ($matches as $match)
		{
			$dice = [
				'operator'		=> $match[1] ? $match[1] : '+',
				'qty'			=> $match[2] ? intval($match[2]) : 1,
				'sides'			=> $match[3] ? ($this->utils->is_numeric($match[3]) ? intval($match[3]) : $match[3]) : 1,
				'fudge'			=> false,
				'explode'		=> (bool) $match[5],
				'penetrate'		=> ((bool) (($match[5] === '!p') || ($match[5] === '!!p'))),
				'compound'		=> ((bool) (($match[5] === '!!') || ($match[5] === '!!p'))),
				'compare_point'	=> false,
				'additions'		=> [],
			];

			// Check dice quantity limit
			if ($this->config['dice_qty_per_dice'] && ($dice['qty'] > $this->config['dice_qty_per_dice']))
			{
				throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICE_QTY', 'TOO_HIGH']);
			}

			// Check dice sides limit, if limit is set and sides is not 100 or % (percentage dice)
			if (!in_array($dice['sides'], ['F', 'F.1', 'F.2', '%', 100]))
			{
				if ($this->config['dice_sides_only'])
				{
					$allowed_sides = $this->functions->get_dice_sides();
					if (!in_array($dice['sides'], $allowed_sides))
					{
						throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_SIDES', 'NOT_ALLOWED']);
					}
				}

				if ($this->config['dice_sides_per_dice'] && ($dice['sides'] > $this->config['dice_sides_per_dice']))
				{
					throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_SIDES', 'TOO_HIGH']);
				}
			}

			// Add the dice quantity to the overall count
			$quantity += $dice['qty'];
			$exploding = $dice['explode'] ? ++$exploding : $exploding;
			$penetrating = $dice['penetrate'] ? ++$penetrating : $penetrating;
			$compounding = $dice['compound'] ? ++$compounding : $compounding;
			$percentage = in_array($dice['sides'], ['%', 100]) ? ++$percentage : $percentage;

			// Check if it's a fudge dice
			if (gettype($dice['sides']) === 'string')
			{
				$dice['fudge'] = preg_match($this->regex->get('fudge', true), $dice['sides'], $fudge_matches) ? $fudge_matches : false;
				$fudge = $dice['fudge'] ? ++$fudge : $fudge;
			}

			// Check if we have a compare point
			if ($match[6])
			{
				$dice['compare_point'] = [
					'operator'	=> $match[6],
					'value'		=> intval($match[7]),
				];
			}
			else if ($dice['explode'])
			{
				// we are exploding the dice so we need a compare point, but none has been defined
				$dice['compare_point'] = [
					'operator'	=> '=',
					'value'		=> $dice['fudge'] ? 1 : ($dice['sides'] === '%') ? 100 : $dice['sides'],
				];
			}

			// Check if we have additions
			if (isset($match[8]))
			{
				// we have additions (ie. +2, -L)
				preg_match_all($this->regex->get('addition'), $match[8], $additions, PREG_SET_ORDER);

				foreach ($additions as $addition)
				{
					// add the addition to the list
					$dice['additions'][] = [
						// addition operator for concatenating with the dice (+, -, /, *)
						'operator'	=> $addition[1],
						// addition value - either numerical or string 'L' or 'H'
						'value'		=> $this->utils->is_numeric($addition[2]) ? intval($addition[2]) : $addition[2],
					];
				}
			}

			// Add the dice to the list
			$dices[] = $dice;
		}

		// Check dice limit
		if ($this->config['dice_per_notation'] && (count($dices) > $this->config['dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICES', 'TOO_HIGH']);
		}

		// Check dice quantity limit
		if ($this->config['dice_qty_dice_per_notation'] && ($quantity > $this->config['dice_qty_dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICES_QTY', 'TOO_HIGH']);
		}

		// Check percentage dice limit
		if ($this->config['dice_pc_dice_per_notation'] && ($percentage > $this->config['dice_pc_dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICE_PERCENT', 'TOO_HIGH']);
		}

		// Check fudge dice limit
		if ($this->config['dice_fudge_dice_per_notation'] && ($fudge > $this->config['dice_fudge_dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICE_FUDGE', 'TOO_HIGH']);
		}

		// Check exploding dice limit
		if ($this->config['dice_exploding_dice_per_notation'] && ($exploding > $this->config['dice_exploding_dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICE_EXPLODE', 'TOO_HIGH']);
		}

		// Check penetrating dice limit
		if ($this->config['dice_penetration_dice_per_notation'] && ($penetrating > $this->config['dice_penetration_dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICE_PENETRATE', 'TOO_HIGH']);
		}

		// Check compounding dice limit
		if ($this->config['dice_compound_dice_per_notation'] && ($compounding > $this->config['dice_compound_dice_per_notation']))
		{
			throw new \phpbbstudio\dice\exception\unexpected_value(['ROLL_DICE_COMPOUND', 'TOO_HIGH']);
		}

		// JSON encode
		$dices = json_encode($dices);

		// Add the dices to the data array
		$this->data['roll_dices'] = $dices;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_rolls()
	{
		return isset($this->data['roll_rolls']) ? (array) json_decode($this->data['roll_rolls']) : [0];
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_rolls(array $rolls)
	{
		// Enforce data type
		$rolls = (array) $rolls;

		// JSON encode
		$rolls = json_encode($rolls);

		// Add the rolls to the data array
		$this->data['roll_rolls'] = $rolls;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_display($skin, $dir, $ext = '')
	{
		$display = [];

		// loop through and build the string for die rolled
		foreach ($this->get_dices() as $index => $dice)
		{
			$rolls = isset($this->get_rolls()[$index]) ? $this->get_rolls()[$index] : [];
			$has_compare_point = !empty($dice['compare_point']);

			// Current roll total - used for totalling compounding rolls
			$current_roll = 0;
			$compounded_rolls = [];

			$display_dice = ['OPERATOR' => $dice['operator']];

			// Output the rolls
			foreach ($rolls as $roll_index => $roll)
			{
				$display_roll = [];

				// get the roll value to compare to (If penetrating and not the first roll, add 1, to compensate for the penetration)
				$roll_val = ($dice['penetrate'] && $current_roll) ? $roll + 1 : $roll;
				$has_matched_cp = $has_compare_point && $this->utils->is_compare_point($dice['compare_point'], $roll_val);

				$delimit = true;

				if ($dice['explode'] && $has_matched_cp)
				{
					// this die roll exploded (Either matched the explode value or is greater than the max - exploded and compounded)

					// add the current roll to the roll total
					$current_roll += $roll;

					if ($dice['compound'])
					{
						$compounded_rolls[] = $roll;

						// do NOT add the delimiter after this roll as we're not outputting it
						$delimit = false;
					}
					else
					{
						$display_roll['ROLL'] = $roll;
						$display_roll['TOOLTIP']['S_EXPLODE'] = $this->lang->lang('DICE_ROLL_EXPLODED');
						if ($dice['penetrate'])
						{
							$display_roll['TOOLTIP']['S_PENETRATE'] = $this->lang->lang('DICE_ROLL_PENETRATED');
						}					}
				}
				else if ($has_matched_cp)
				{
					// not exploding but we've matched a compare point - this is a pool dice (success or failure)
					$display_roll['ROLL'] = $roll;
					$display_roll['TOOLTIP']['S_SUCCESS'] = $this->lang->lang('SUCCESS');
				}
				else if ($dice['compound'] && $current_roll)
				{
					// last roll in a compounding set (This one didn't compound)
					$display_roll['ROLL'] = ($roll + $current_roll);
					$display_roll['TOOLTIP']['S_EXPLODE'] = $this->lang->lang('DICE_ROLL_EXPLODED');
					$display_roll['TOOLTIP']['S_COMPOUND'] = $this->lang->lang('DICE_ROLL_COMPOUNDED');
					if ($dice['penetrate'])
					{
						$display_roll['TOOLTIP']['S_PENETRATE'] = $this->lang->lang('DICE_ROLL_PENETRATED');
					}
					$display_roll['COMPOUNDED_ROLLS'] = $compounded_rolls;

					// Reset current roll total
					$current_roll = 0;
					$compounded_rolls = [];
				}
				else
				{
					// Just a normal roll
					$display_roll['ROLL'] = $roll;
				}

				if ($delimit)
				{
					// Check if an image exists.
					if ($skin !== 'text')
					{
						$img_alt = $this->functions->get_dice_image_notation($dice['sides'], $display_roll['ROLL'], $ext);
						$img_src = $dir . $skin . '/' . $img_alt;

						$display_roll['IMAGE'] = $this->functions->check_dice_dir($img_src) ? $this->functions->update_dice_img_path($img_src) : '';
					}

					$display_dice['ROLLS'][] = $display_roll;
				}
			}

			// Add any additions
			if (!empty($dice['additions']))
			{
				$display_dice['ADDITIONS'] = array_reduce($dice['additions'], function($prev, $current)
				{
					return $prev . $current['operator'] . $current['value'];
				}, '');

				// Actual values of the rolls for the purposes of L/H modifiers
				$rolls_values = $dice['compound'] ? array_reduce($rolls, function($a, $b)
				{
					return $a + $b;
				}, 0) : $rolls;

				foreach ($dice['additions'] as $addition)
				{
					switch ($addition['value'])
					{
						case 'H':
							$value_highest = max($rolls_values);

							$key = array_search($value_highest, $rolls_values);
							$display_dice['ROLLS'][$key]['TOOLTIP']['S_HIGHEST'] = $this->lang->lang('DICE_ROLL_HIGHEST');
						break;
						case 'L':
							$value_lowest = min($rolls_values);

							$key = array_search($value_lowest, $rolls_values);
							$display_dice['ROLLS'][$key]['TOOLTIP']['S_LOWEST'] = $this->lang->lang('DICE_ROLL_LOWEST');
						break;
					}
				}
			}

			$display[] = $display_dice;
		}

		return $display;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_output()
	{
		return isset($this->data['roll_output']) ? (string) $this->data['roll_output'] : '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_output()
	{
		$output = '';

		if ($this->get_dices() && is_array($this->get_rolls()) && $this->get_rolls())
		{
			// loop through and build the string for die rolled
			foreach ($this->get_dices() as $index => $dice)
			{
				$rolls = isset($this->get_rolls()[$index]) ? $this->get_rolls()[$index] : [];
				$has_compare_point = !empty($dice['compare_point']);

				// Current roll total - used for totalling compounding rolls
				$current_roll = 0;

				$output .= (($index > 0) ? $dice['operator'] : '') . '[';

				// Output the rolls
				foreach ($rolls as $roll_index => $roll)
				{
					// get the roll value to compare to (If penetrating and not the first roll, add 1, to compensate for the penetration)
					$roll_val = ($dice['penetrate'] && $current_roll) ? $roll + 1 : $roll;
					$has_matched_cp = $has_compare_point && $this->utils->is_compare_point($dice['compare_point'], $roll_val);

					$delimit = $roll_index !== (count($rolls) - 1);

					if ($dice['explode'] && $has_matched_cp)
					{
						// this die roll exploded (Either matched the explode value or is greater than the max - exploded and compounded)

						// add the current roll to the roll total
						$current_roll += $roll;

						if ($dice['compound'])
						{
							// do NOT add the delimiter after this roll as we're not outputting it
							$delimit = false;
						}
						else
						{
							$output .= $roll . '!' . ($dice['penetrate'] ? 'p' : '');
						}
					}
					else if ($has_matched_cp)
					{
						// not exploding but we've matched a compare point - this is a pool dice (success or failure)
						$output .= $roll . '*';
					}
					else if ($dice['compound'] && $current_roll)
					{
						// last roll in a compounding set (This one didn't compound)
						$output .= ($roll + $current_roll) . '!!' . ($dice['penetrate'] ? 'p' : '');

						// Reset current roll total
						$current_roll = 0;
					}
					else
					{
						// Just a normal roll
						$output .= $roll;
					}

					if ($delimit)
					{
						$output .= $this->lang->lang('COMMA_SEPARATOR');
					}
				}

				$output .= ']';

				// Add any additions
				if (!empty($dice['additions']))
				{
					$output .= array_reduce($dice['additions'], function($prev, $current)
					{
						return $prev . $current['operator'] . $current['value'];
					}, '');
				}
			}

			// Add the total
			$output .= ' = ' . $this->get_total();
		}
		else
		{
			$output .= $this->lang->lang('DICE_ROLL_NO_ROLL');
		}

		// Add the result to the data array
		$this->data['roll_output'] = $output;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_total()
	{
		return isset($this->data['roll_total']) ? ((int) $this->data['roll_total'] / 100) : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_total()
	{
		$total = $successes = 0;
		$overall_is_pool = false;

		if ($this->get_dices() && is_array($this->get_rolls()) && $this->get_rolls())
		{
			// Loop through each roll and calculate the totals
			foreach ($this->get_dices() as $index => $dice)
			{
				$rolls = isset($this->get_rolls()[$index]) ? $this->get_rolls()[$index] : [];

				/** @noinspection PhpUnusedLocalVariableInspection */
				$dice_total = 0;

				// Actual values of the rolls for the purposes of L/H modifiers
				$rolls_values = $dice['compound'] ? array_reduce($rolls, function($a, $b)
				{
					return $a + $b;
				}, 0) : $rolls;

				$is_pool = !$dice['explode'] && !empty($dice['compare_point']);

				if ($is_pool)
				{
					/**
					 * Pool dice are success/failure so we don't want the actual dice roll
					 * we need to convert each roll to 1 (success) or 0 (failure)
					 */
					$rolls = array_map(function($value) use ($dice)
					{
						return $this->utils->get_success_state_value($value, $dice['compare_point']);
					}, $rolls);
				}

				// add all the rolls together to get the total
				$dice_total = $this->utils->sum_array($rolls);

				if (!empty($dice['additions']))
				{
					// loop through the additions and handle them
					foreach ($dice['additions'] as $addition)
					{
						$addition_value = $addition['value'];
						$is_pool_modifier = false;

						// run any necessary addition value modifications
						if ($addition_value === 'H')
						{
							// 'H' is equivalent to the highest roll
							$addition_value = max($rolls_values);
							// flag that this value needs to be modified to a success/failure value
							$is_pool_modifier = true;
						}
						else if ($addition_value === 'L')
						{
							// 'L' is equivalent to the lowest roll
							$addition_value = min($rolls_values);
							// flag that this value needs to be modified to a success/failure value
							$is_pool_modifier = true;
						}

						if ($is_pool && $is_pool_modifier)
						{
							// pool dice are either success or failure, so value is converted to 1 or 0
							$addition_value = $this->utils->get_success_state_value($addition_value, $dice['compare_point']);
						}

						// run the actual mathematical equation
						$dice_total = $this->utils->equate_numbers($dice_total, $addition_value, $addition['operator']);
					}
				}

				// Total the value
				$total = $this->utils->equate_numbers($total, $dice_total, $dice['operator']);

				// if this is a pool dice, add it's success count to the count
				if ($is_pool)
				{
					$successes = $this->utils->equate_numbers($successes, $dice_total, $dice['operator']);
				}

				// Update if this entire roll had any pool dice.
				$overall_is_pool = $overall_is_pool ? $overall_is_pool : $is_pool;
			}
		}

		// Set the successes
		$this->set_successes($successes);

		// Set the is_pool status
		$this->set_is_pool($overall_is_pool);

		// Round to two decimals
		$total = round($total, 2, PHP_ROUND_HALF_UP);

		// Make it an integer
		$total = $total * 100;

		// Enforce data type
		$total = (int) $total;

		// Add the total to the data array
		$this->data['roll_total'] = $total;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_successes()
	{
		return isset($this->data['roll_successes']) ? (int) $this->data['roll_successes'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_successes($successes)
	{
		// Enforce data type
		$successes = (int) $successes;

		/**
		 * If the data is out of range we'll throw an exception. We use 65535 as a
		 * maximum because it matches the MySQL unsigned small int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB.
		 */
		if ($successes < 0 || $successes > 65535)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_SUCCESSES', 'ROLL_USINT']);
		}

		// Add the successes to the data array
		$this->data['roll_successes'] = $successes;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_is_pool()
	{
		return isset($this->data['roll_is_pool']) ? (bool) $this->data['roll_is_pool'] : false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_is_pool($is_pool)
	{
		// Enforce data type
		$is_pool = (bool) $is_pool;

		// Add the is_pool status to the data array
		$this->data['roll_is_pool'] = $is_pool;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_time()
	{
		return isset($this->data['roll_time']) ? (int) $this->data['roll_time'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_time($time)
	{
		// Enforce data type
		$time = (int) $time;

		/*
		* If the data is out of range we'll throw an exception. We use 4294967295 as a
		* maximum because it matches the MySQL unsigned large int maximum value which
		* is the lowest amongst the DBMS supported by phpBB. ULINT equals UNIX TIMESTAMP.
		*/
		if ($time < 0 || $time > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_TIME', 'ROLL_ULINT']);
		}

		// Add the time to the data array
		$this->data['roll_time'] = $time;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_edit_user()
	{
		return isset($this->data['roll_edit_user']) ? (int) $this->data['roll_edit_user'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_edit_user($id)
	{
		// Enforce data type
		$id = (int) $id;

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB.
		 */
		if ($id < 0 || $id > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_EDIT_USER', 'ROLL_ULINT']);
		}

		// Add the identifier to the data array
		$this->data['roll_edit_user'] = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_edit_time()
	{
		return isset($this->data['roll_edit_time']) ? (int) $this->data['roll_edit_time'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_edit_time($time)
	{
		// Enforce data type
		$time = (int) $time;

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB. ULINT equals UNIX TIMESTAMP.
		 */
		if ($time < 0 || $time > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_EDIT_TIME', 'ROLL_ULINT']);
		}

		// Add the time to the data array
		$this->data['roll_edit_time'] = $time;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_edit_count()
	{
		return isset($this->data['roll_edit_count']) ? (int) $this->data['roll_edit_count'] : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_edit_count($count)
	{
		// Enforce data type
		$count = (int) $count;

		/**
		 * If the data is out of range we'll throw an exception. We use 4294967295 as a
		 * maximum because it matches the MySQL unsigned large int maximum value which
		 * is the lowest amongst the DBMS supported by phpBB. ULINT equals UNIX TIMESTAMP.
		 */
		if ($count < 0 || $count > 4294967295)
		{
			throw new \phpbbstudio\dice\exception\out_of_bounds(['ROLL_EDIT_COUNT', 'ROLL_ULINT']);
		}

		// Add the count to the data array
		$this->data['roll_edit_count'] = $count;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function increment_edit_count()
	{
		$count = $this->get_edit_count();

		$count++;

		$this->set_edit_count($count);

		return $this;
	}
}
