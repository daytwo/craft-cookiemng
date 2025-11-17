<?php

namespace daytwo\cookiemng\pluginassets;

/**
* Cookie Management module for Craft CMS 4.x
*
*
* @link      https://daytwo.no
* @copyright Copyright (c) 2024 Daytwo
*/

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Kurious Agency
 * @package   Bundles
 * @since     1.0.0
 */
class PluginAssets extends AssetBundle
{
	// Public Methods
	// =========================================================================

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->sourcePath =
			"@daytwo/cookiemng/pluginassets/dist";

		$this->js = ['js/cookiemng.js', 'js/cookiemng-async.js'];

		$this->css = ['css/cookiemng.css'];

		parent::init();
	}
}
