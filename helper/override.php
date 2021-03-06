<?php
/**
 * @package     RedCORE.Plugin
 * @subpackage  System.MVCOverride
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Class MVCOverrideHelperOverride
 *
 * @since  1.4
 */
abstract class MVCOverrideHelperOverride
{
	/**
	 * Default suffix of class overrides
	 *
	 * @var string
	 */
	const SUFFIX = 'Default';

	/**
	 * Default pre-suffix of class overrides
	 *
	 * @var string
	 */
	const PREFIX = '';

	/**
	 * Get Original Class
	 *
	 * @param   string  $bufferContent  Buffer Content
	 *
	 * @return null|string
	 */
	static public function getOriginalClass($bufferContent)
	{
		$originalClass = null;
		$tokens = token_get_all($bufferContent);

		foreach ($tokens as $key => $token)
		{
			if (is_array($token))
			{
				// Find the class declaration
				if (token_name($token[0]) == 'T_CLASS')
				{
					// Class name should be in the key+2 position
					$originalClass = $tokens[$key + 2][1];
					break;
				}
			}
		}

		return $originalClass;
	}

	/**
	 * Read source file and replace class name by adding suffix/prefix
	 *
	 * @param   string  $componentFile  Component File
	 * @param   string  $prefix         Prefix
	 * @param   string  $suffix         Suffix
	 *
	 * @return  string
	 */
	static public function createDefaultClass($componentFile, $prefix = null, $suffix = null)
	{
		$bufferFile = file_get_contents($componentFile);

		$originalClass = self::getOriginalClass($bufferFile);

		// Set default values if null
		if (is_null($suffix))
		{
			$suffix = self::SUFFIX;
		}

		if (is_null($prefix))
		{
			$prefix = self::PREFIX;
		}

		$replaceClass = $prefix . $originalClass . $suffix;

		// Replace original class name by default
		$bufferContent = str_replace($originalClass, $replaceClass, $bufferFile);

		return $bufferContent;
	}

	/**
	 * Load buffer content
	 *
	 * @param   string  $bufferContent  Buffer Content
	 *
	 * @return  void
	 */
	static public function load($bufferContent)
	{
		if (!empty($bufferContent))
		{
			eval('?>' . $bufferContent . PHP_EOL . '?>');
		}
	}
}
