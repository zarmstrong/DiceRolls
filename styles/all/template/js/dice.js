/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

(function($) { // Avoid conflicts with other libraries

	/**
	 * Set up an empty array that will contain all our functions.
	 */
	let dice = {};

	'use strict';

	/**
	 * Set up all our elements
	 * @var {object}	$rollTable			The table where all rolls are listed
	 * @var {object}	$rollButton			The button to create a roll
	 * @var {object}	$rollInsert			The buttons inside the dice rolls table that insert a roll into the textarea
	 * @var {object}	$rollNotation		The inputbox where the dice notation is filled in
	 * @var {object}	$rollIndicator		The hidden checkbox indicating that new rolls were added
	 * @var {object}	$rollTemplate		The row template that is used to add new table rows
	 */
	let $rollTable = $('#dice_table');
	let $rollButton = $('#dice_roll');
	let $rollInsert = $('[name="dice_insert"]');
	let $rollNotation = $('#dice_notation');
	let $rollIndicator = $('#dice_indicator');
	let $rollTemplate = $('#dice_template');

	$(function() {
		// Get the roll template row
		dice.rollTemplate = $rollTemplate[0].outerHTML;
		$rollTemplate.remove();

		// Bind all 'Insert roll into textarea'-buttons
		$rollInsert.each(function() {
			$(this).on('click', function() {
				// Send the roll identifier and notation to the function
				dice.insertRollRow($(this).data('roll-id'), $(this).data('roll-notation'));
			});
		});

		// Bind the 'Create a roll'-button
		$rollButton.on('click', function(event) {
			/**
			 * @var {string}	action		The AJAX action
			 * @var {string}	notation	The roll notation
			 */
			let action = $rollButton.data('url'),
				notation = $rollNotation.val();

			/**
			 * Set up a 'Create a roll'-function
			 *
			 * @returns {void}
			 */
			let rollDice = function() {
				// Set a loading indicator
				let $loadingIndicator = phpbb.loadingIndicator();

				// Set up the AJAX request
				let request = $.ajax({
					url: action,
					type: 'GET',
					data: {notation: notation},
					cache: false,
					success: dice.getRollResponse,
				});

				// After the request (success or error) remove the loading indicator
				request.always(function() {
					if ($loadingIndicator && $loadingIndicator.is(':visible')) {
						$loadingIndicator.fadeOut(phpbb.alertTime);
					}
				});
			};

			// Call the 'Create a roll'-function
			rollDice();

			// Prevent any default actions by clicking on the button
			event.preventDefault();
		});
	});

	/**
	 * Response handler for adding a dice roll.
	 *
	 * @param	{array}		response
	 * @param	{bool}		response.ROLL_SUCCESS
	 * @param	{bool}		response.ROLL_LIMIT
	 * @param	{array}		response.ROLL_DATA
	 * @param	{string}	response.MESSAGE_TITLE
	 * @param	{string}	response.MESSAGE_TEXT
	 * @returns {void}
	 */
	dice.getRollResponse = function (response) {
		phpbb.clearLoadingTimeout();

		// Show the response message
		phpbb.alert(response.MESSAGE_TITLE, response.MESSAGE_TEXT);

		if (response.ROLL_SUCCESS) {
			// If the request is successful, we auto close the message after 2 seconds
			phpbb.closeDarkenWrapper(2000);

			// We reset the notation input field
			dice.setRollNotation('');

			// We set the indicator that a roll was added
			dice.setRollIndicator();

			// We add the roll to the table
			dice.setRollRow(response.ROLL_DATA);

			// We toggle the roll button if necessary
			dice.toggleRollButton(response.ROLL_LIMIT);
		}
	};

	/**
	 * Set a roll notation.
	 *
	 * @param	{string} value	The roll notation
	 * @returns {void}
	 */
	dice.setRollNotation = function(value) {
		$rollNotation.val(value);
	};

	/**
	 * Set the roll indicator as true.
	 *
	 * @returns {void}
	 */
	dice.setRollIndicator = function() {
		$rollIndicator.prop('checked', true);
	};

	/**
	 * Toggle the roll button if necessary.
	 *
	 * @param	{bool}	status	The status for the roll button (enabled|disabled)
	 * @returns {void}
	 */
	dice.toggleRollButton = function(status) {
		// If the status is different than the current, we swap some thing around
		if (status !== $rollButton.prop('disabled')) {
			let icon = $rollButton.find('i'),
				span = $rollButton.find('span'),
				text = span.text();

			// Toggle class
			$rollButton.toggleClass('button-secondary');

			// Toggle icons
			icon.toggleClass($rollButton.data('icons'));

			// Toggle text
			span.text($rollButton.data('text'));
			$rollButton.data('text', text);
		}

		// Set the status
		$rollButton.prop('disabled', status);
	};

	/**
	 * Add a roll row to the table.
	 *
	 * @param	{array}		roll			The roll data
	 * @param	{int}		roll.id			The roll identifier
	 * @param	{string}	roll.notation	The roll notation
	 * @param	{string}	roll.time		The roll time
	 * @returns {void}
	 */
	dice.setRollRow = function(roll) {
		// Make sure the table is no longer hidden.
		$rollTable.parent('.inner').parent('.panel').removeClass('hidden');

		// Get the row template
		let row = $(dice.rollTemplate);

		// Set the data
		row.find('.dice-roll-id').html(roll.id);
		row.find('.dice-roll-notation').html(roll.notation);
		row.find('.dice-roll-time').html(roll.time);
				row.find('.dice-roll-actions button')
			.data('roll-id', roll.id)
			.data('roll-notation', roll.notation)
			.on('click', function() {
				// Bind a click event for this button
				dice.insertRollRow(roll.id, roll.notation);
			});
		row.find('.dice-roll-actions a').attr('href', function(i, url) {
			return url + '/' + roll.id;
		}).each(function() {
			// Register this newly created ajax callback
			let $this = $(this),
				ajax = $this.attr('data-ajax'),
				filter = $this.attr('data-filter');

			if (ajax !== 'false') {
				let fn = (ajax !== 'true') ? ajax : null;
				filter = (filter !== undefined) ? phpbb.getFunctionByName(filter) : null;

				phpbb.ajaxify({
					selector: this,
					refresh: $this.attr('data-refresh') !== undefined,
					filter: filter,
					callback: fn
				});
			}
		});

		// Append the row
		$rollTable.css('display', 'none').append(row).slideDown('slow').removeAttr('style');
	};

	/**
	 * Insert a roll into the textarea.
	 *
	 * @param	{int}		id				The roll identifier
	 * @param	{string}	notation		The roll notation
	 * @returns {void}
	 */
	dice.insertRollRow = function(id, notation) {
		insert_text('[roll=' + id + ']' + notation + '[/roll]');
	};

	/**
	 * Add a callback for deleting a roll.
	 *
	 * @param	{array}		response				The response array
	 * @param	{bool}		response.ROLL_SUCCESS	Successful deletion indicator
	 * @param	{bool}		response.ROLL_LIMIT		Limit max rolls reached
	 * @param	{int}		response.ROLL_ID		The roll identifier
	 * @returns {void}
	 */
	phpbb.addAjaxCallback('dice_delete', function(response) {
		if (response.ROLL_SUCCESS) {
			// We toggle the roll button if necessary
			dice.toggleRollButton(response.ROLL_LIMIT);

			// Remove the row
			$(this).parents('tr').slideUp('slow', function() {
				$(this).remove();
			});

			/**
			 * @var {RegExp}	pattern				Regular expression to find the dice bbcode in the text
			 * @var {object}	textarea			The textarea
			 */
			let roll_id = response.ROLL_ID,
				pattern = new RegExp('\\[roll=' + roll_id + '\\].*?\\[\\/roll\\]', 'gi'),
				textarea = document.forms[form_name].elements[text_name];

			textarea.value = textarea.value.replace(pattern, '');
		}
	});

	/**
	 * Add a callback for editing a roll.
	 *
	 * @param	{array}		response				The response array
	 * @param	{bool}		response.ROLL_SUCCESS	Successful deletion indicator
	 * @param	{int}		response.ROLL_DATA		The roll data
	 * @returns {void}
	 */
	phpbb.addAjaxCallback('dice_edit', function(response) {
		if (response.ROLL_SUCCESS) {
			let row		= $(this).parents('tr'),
				roll	= response.ROLL_DATA,
				pattern	= new RegExp('\\[roll=' + roll.id + '\\].*?\\[\\/roll\\]', 'gi'),
				textarea = document.forms[form_name].elements[text_name];

			row.find('.dice-roll-notation').html(roll.notation);

			textarea.value = textarea.value.replace(pattern, '[roll=' + roll.id +']' + roll.notation + '[/roll]');
		}
	});
})(jQuery); // Avoid conflicts with other libraries
