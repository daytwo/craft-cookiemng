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

  #TWIG => {{ craft.cookiemng.setPermissionCookie$value, $duration, $secure, $http_only) }}
  public function setPermissionCookie($value, $duration)
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
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
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();
    $env = CookieMng::$instance->getEnvValues();

    if (!$env->cookieEnabled){
      return null;
    }

    return CookieMng::$instance->services->getPermissionCookie();
  }
  
  #TWIG => {{ craft.cookiemng.render()|raw }}
  public function render($siteHandle = "default")
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();
    //$env = CookieMng::$instance->getEnvValues();

    if (!$settings->getCookieEnabled($siteHandle)){
      return '';
    }

    $permissions = CookieMng::$instance->services->getPermissionCookie($siteHandle);
    $permissions = $permissions ? $permissions : '';
    return Craft::$app->view->renderTemplate('cookiemng/panel/bar.twig',['settings'=>$settings,'permissions'=>$permissions ? explode(',',$permissions) : false,'siteHandle'=>$siteHandle],View::TEMPLATE_MODE_CP);
  }

  #TWIG => {{ craft.cookiemng.consentTemplate()|raw }}
  public function consentTemplate($siteHandle = "default")
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();
    //$env = CookieMng::$instance->getEnvValues();

    if (!$settings->getCookieEnabled($siteHandle)){
      return '';
    }
        
    $permissions = CookieMng::$instance->services->getPermissionCookie($siteHandle);
    $permissions = $permissions ? $permissions : '';
    return Craft::$app->view->renderTemplate('cookiemng/panel/consentTemplateV2.twig',['settings'=>$settings,'permissions'=>explode(',',$permissions),'siteHandle'=>$siteHandle],View::TEMPLATE_MODE_CP);
  }
}
