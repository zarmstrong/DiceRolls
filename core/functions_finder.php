<?php
/**
 * phpBB Studio's Dice extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019 phpBB Studio <https://www.phpbbstudio.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbstudio\dice\core;

use \Symfony\Component\Finder\Finder;

/**
 * phpBB Studio's Dice Finder functions.
 */
class functions_finder
{
	/**
	 * Find available dice skins in the given directory.
	 *
	 * @param  string	$directory		The dice skins directory
	 * @return array					Array of available dice skins
	 * @access public
	 */
	public function find_dice_skins($directory)
	{
		$skins = [];

		$finder = new Finder;
		$finder->ignoreUnreadableDirs()
				->in($directory)
				->directories()
				->depth('== 0');

		foreach($finder as $dir)
		{
			$skin = $dir->getBasename();

			$skins[] = $skin;
		}

		return (array) $skins;
	}

	/**
	 * Find available dice skin images for a given dice skin.
	 *
	 * @param  string	$directory		The dice skins directory
	 * @param  string	$skin			The dice skin
	 * @param  array	$exts			Array of allowed image extensions
	 * @return array					Array of available dice skin images
	 * @access public
	 */
	public function find_dice_images($directory, $skin, array $exts)
	{
		$images = [];

		$finder = new Finder;
		$finder->in($directory . $skin)
				->files()
				->depth('== 0');

		foreach ($finder as $image)
		{
			if (in_array($image->getExtension(), $exts))
			{
				$images[] = $image->getBasename('.' . $image->getExtension());
			}
		}

		return (array) $images;
	}
}
