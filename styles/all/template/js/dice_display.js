/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

(function($) { // Avoid conflicts with other libraries

	'use strict';

	let h = 38;
	let w = 300;
	let d = 'dice-tooltip';
	let t = 'dice-tooltip-top';
	let r = 'dice-tooltip-right';
	let b = 'dice-tooltip-bottom';
	let l = 'dice-tooltip-left';
	let f = 'dice-tooltip-flip';

	$(function() {
		$('.dice-tooltip').each(function() {
			let parent = $(this).parents('.content, dd').first(),
				tt = $(this).offset().top,
				pt = parent.offset().top,
				th = $(this).height(),
				ph = parent.height(),
				tb = tt + th,
				pb = pt + ph,
				ts = $(this).offset().left,
				ps = parent.offset().left,
				pe = ps + parent.width();

			if (tt - pt > h) {
				$(this).addClass(t);
				if (pe - ts < w) {
					$(this).addClass(f);
				}
			} else if (pb - tb > h) {
				$(this).addClass(b);
				if (pe - ts < w) {
					$(this).addClass(f);
				}
			} else if (pe - ts > w) {
				$(this).addClass(r);
			} else if (ts - ps > w)  {
				$(this).addClass(r);
			} else { // Not able to place a tooltip
				$(this).removeClass(d);
			}
		});
	});
})(jQuery); // Avoid conflicts with other libraries
