/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

(function($) {

'use strict';

let $dark = $('#darkenwrapper');
let $alert = $('#phpbb_alert');
let keymap = {
	ENTER: 13,
	ESC: 27
};

phpbb.addAjaxCallback('dice_refresh', function() {
	// Do not allow closing alert
	$dark.off('click');
	$alert.find('.alert_close').hide();

	$(document).on('keydown.phpbb.alert', function(e) {
		if (e.keyCode === keymap.ENTER || e.keyCode === keymap.ESC) {
			window.location.reload();
		}
	});
});

})(jQuery);
