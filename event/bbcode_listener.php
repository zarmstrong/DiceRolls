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
 * phpBB Studio's Dice BBCode listener.
 */
class bbcode_listener implements EventSubscriberInterface
{
	/** @var \phpbbstudio\dice\core\functions_common */
	protected $functions;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * Constructor.
	 *
	 * @param \phpbbstudio\dice\core\functions_common	$functions	Common functions
	 * @param \phpbb\request\request					$request	Request object
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbbstudio\dice\core\functions_common $functions,
		\phpbb\request\request $request
	)
	{
		$this->functions	= $functions;
		$this->request		= $request;
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
			'core.text_formatter_s9e_configure_after'	=> 'set_dice_bbcode',
			'core.text_formatter_s9e_parse_before'		=> 'set_dice_availability',
		];
	}

	/**
	 * Add the roll dice bbcode.
	 *
	 * @event  core.text_formatter_s9e_configure_after
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function set_dice_bbcode($event)
	{
		/* Get the BBCode configurator */
		$configurator = $event['configurator'];

		$configurator->attributeFilters->set('#dicenotation', __CLASS__ . '::dice_notation');

		/* Let's unset any existing BBCode that might already exist */
		unset($configurator->BBCodes['roll']);
		unset($configurator->tags['roll']);

		/* Let's create the new BBCode */
		$configurator->BBCodes->addCustom( '[roll={NUMBER}]{DICENOTATION}[/roll]', '<span class="phpbbstudio-dice" data-dice-id="{NUMBER}">{DICENOTATION}</span>');
	}

	/**
	 * Check if the roll dice bbcode should be parsed in this forum.
	 *
	 * @event  core.text_formatter_s9e_parse_before
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function set_dice_availability($event)
	{
		/** @var \phpbb\textformatter\s9e\parser $parser */
		$parser = $event['parser'];

		$forum_id = (int) $this->request->variable('f', 0);

		$dice_enabled = (bool) $this->functions->forum_enabled($forum_id);

		($dice_enabled) ? $parser->enable_bbcode('roll') : $parser->disable_bbcode('roll');
	}

	/**
	 * Set the filter using within the roll dice bbcode.
	 *
	 * @param  string				$string		The dice notation from within the bbcode
	 * @return string				$notation	The correctly formatted dice notation
	 * @access public
	 */
	public static function dice_notation($string)
	{
		$notation = str_replace(['D', 'f', 'h', 'l', 'P'], ['d', 'F', 'H', 'L', 'p'], $string);
		//$notation = preg_replace('[^0-9dFHLp\+\-\*/!\.%=<>\(\)]', '', $notation);
		$notation = preg_replace('([^0-9dFHLp\+\-\*/!\.%=<>\(\)])', '', $notation);

		return $notation;
	}
}
