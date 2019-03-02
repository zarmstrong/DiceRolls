<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\migrations;

/**
 * Install permissions.
 */
class install_permissions extends \phpbb\db\migration\migration
{
	/**
	 * Assign migration file dependencies for this migration.
	 *
	 * @return array		Array of migration files
	 * @access public
	 * @static
	 */
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v32x\v325');
	}

	/**
	 * Add the dice extension permissions to the database.
	 *
	 * @return array 		Array of permissions
	 * @access public
	 */
	public function update_data()
	{
		return array(
			/* Forum User permissions */
			array('permission.add', array('f_dice_roll', false)),			/* Can roll dice */
			array('permission.add', array('f_dice_edit', false)),			/* Can edit a rolled dice */
			array('permission.add', array('f_dice_delete', false)),			/* Can delete own dice */
			array('permission.add', array('f_dice_no_limit', false)),		/* Can ignore dice limit per post */
			array('permission.add', array('f_dice_view', false)),			/* Can view rolled dice */

			/* Forum Moderator permissions */
			array('permission.add', array('f_mod_dice_add', false)),		/* Can roll dice on other users' post */
			array('permission.add', array('f_mod_dice_edit', false)),		/* Can edit rolled dice on other users' post */
			array('permission.add', array('f_mod_dice_delete', false)),		/* Can delete dice on other users' post */

			/* Admin Group permissions */
			array('permission.add', array('a_dice_admin')),					/* Can administer the extension's ACP */

			/* Registered user Group permissions */
			array('permission.add', array('u_dice_use_ucp')),				/* Can manage the extension's UCP */
			array('permission.add', array('u_dice_test')),					/* Can use the 'test notation'page */
			array('permission.add', array('u_dice_skin')),					/* Can change dice skin */
		);
	}
}
