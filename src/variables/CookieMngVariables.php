<?php

namespace daytwo\cookiemng\variables;

/**
* Cookie Management module for Craft CMS 4.x
*
*
* @link      https://daytwo.no
* @copyright Copyright (c) 2024 Daytwo
*/


use Craft;

use daytwo\cookiemng\CookieMng;
use daytwo\cookiemng\pluginassets\PluginAssets;
use craft\web\View;

/**
* Class PermissionVariables
*
* @author    Daytwo
* @package   cookiemng
* @since     1.0.0
*
*/

class CookieMngVariables
{

  public function skipCookiePanelDisplay($siteHandle = "default",$segments){
    if($segments && count($segments) > 0){
      $settings = CookieMng::$instance->getSettings();
      $split = explode('/',$settings->getCookiesReadMoreLink($siteHandle));
      return ($segments[count($segments) - 1]) === $split[count($split) - 1];
    }
    return false;
  }
  #TWIG => {{ craft.cookiemng.setPermissionCookie$value, $duration, $secure, $http_only) }}
  public function setPermissionCookie($value, $duration)
  {
    $settings = CookieMng::$instance->getSettings();
    $env = CookieMng::$instance->getEnvValues();

    if (!$env->cookieEnabled){
      return null;
    }

    return CookieMng::$instance->services->setPermissionCookie($value, $duration, $secure, $http_only);
  }

  #TWIG => {{ craft.cookiemng.getPermissionCookie($name) }}
  public function getPermissionCookie()
  {
    $settings = CookieMng::$instance->getSettings();
    $env = CookieMng::$instance->getEnvValues();

    if (!$env->cookieEnabled){
      return null;
    }

    return CookieMng::$instance->services->getPermissionCookie();
  }
  
  #TWIG => {{ craft.cookiemng.render()|raw }}
  public function render($siteHandle = "default", $hidenInReadMore = false, $deactivated = false)
  {
    if($hidenInReadMore){
      $segments =Craft::$app->getRequest()->segments;
      if($segments && count($segments) > 0){
        $settings = CookieMng::$instance->getSettings();
        $split = explode('/',$settings->getCookiesReadMoreLink($siteHandle));
        if($settings->getCookiesReadMoreLink($siteHandle) != '/' && substr($settings->getCookiesReadMoreLink($siteHandle), 0, 1) == '/'){
          array_shift($split);
        }
        $matches = 0;
        if(count($segments) == count($split)){
          for($i=count($segments)-1; $i>=0; $i--){
            if($segments[$i] === $split[$i]){
              $matches++;
            }
          }
        }
        $deactivated = ($matches === count($split));
      }
    }


  $this->registerFrontendAssets();
    $settings = CookieMng::$instance->getSettings();

    if (!$settings->getCookieEnabled($siteHandle)){
      return '';
    }

    // Return placeholder template for async loading to avoid CDN caching
    return Craft::$app->view->renderTemplate('cookiemng/panel/placeholder.twig',['siteHandle'=>$siteHandle,'deactivated'=>$deactivated],View::TEMPLATE_MODE_CP);
  }

  #TWIG => {{ craft.cookiemng.consentTemplate()|raw }}
  public function consentTemplate($siteHandle = "default")
  {
    $settings = CookieMng::$instance->getSettings();

    if (!$settings->getCookieEnabled($siteHandle)){
      return '';
    }
    
    // Return empty string - consent template is now loaded asynchronously
    // The async loader (cookiemng-async.js) will inject the consent script
    // This prevents cookie state from being cached in the HTML
    return '';
  }

  private function registerFrontendAssets(): void
  {
    if (!class_exists(PluginAssets::class, false)) {
      $path = Craft::getAlias('@daytwo/cookiemng/pluginassets/PluginAssets.php');
      if ($path && is_file($path)) {
        require_once $path;
      }
    }

    if (!class_exists(PluginAssets::class, false)) {
      Craft::error('CookieMng assets could not be loaded from @daytwo/cookiemng/pluginassets/PluginAssets.php', __METHOD__);
      return;
    }

    Craft::$app->view->registerAssetBundle(PluginAssets::class);
  }
}
