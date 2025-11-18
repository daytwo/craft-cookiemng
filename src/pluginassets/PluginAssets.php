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
use craft\helpers\App;
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
	public array $publishOptions = [
		'hashCallback' => [self::class, 'hashForCookieMng'],
	];

	public function init()
	{
		$this->sourcePath =
			"@daytwo/cookiemng/pluginassets/dist";

		$this->js = ['js/cookiemng.js', 'js/cookiemng-async.js'];

		$this->css = ['css/cookiemng.css'];

		parent::init();
	}

	public static function hashForCookieMng(string $path): string
	{
		$realPath = App::normalizePath($path);
		$source = App::normalizePath(Craft::getAlias('@daytwo/cookiemng/pluginassets/dist'));

		if ($realPath === $source) {
			$plugin = Craft::$app->getPlugins()->getPlugin('cookiemng');
			$version = $plugin ? $plugin->getVersion() : 'latest';
			$versionSlug = preg_replace('/[^A-Za-z0-9\-]/', '-', $version);

			return sprintf('cookiemng-%s', $versionSlug ?: 'latest');
		}

		return md5($path);
	}
}
