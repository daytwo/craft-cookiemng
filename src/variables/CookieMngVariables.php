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
  public function setPermissionCookie($value, $duration, $secure=true, $http_only=true)
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();
    
    $enabled = Craft::parseEnv($settings->enabledCookieBar);
    if($enabled == 1){
      $enabled = true;
    }else{
      $enabled = false;
    }
    if (!$enabled){
      return null;
    }

    return CookieMng::$instance->services->setPermissionCookie($value, $duration, $secure, $http_only);
  }

  #TWIG => {{ craft.cookiemng.getPermissionCookie($name) }}
  public function getPermissionCookie()
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();
    
    $enabled = Craft::parseEnv($settings->enabledCookieBar);
    if($enabled == 1){
      $enabled = true;
    }else{
      $enabled = false;
    }
    if (!$enabled){
      return null;
    }

    return CookieMng::$instance->services->getPermissionCookie();
  }
  
  #TWIG => {{ craft.cookiemng.render()|raw }}
  public function render()
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();
    
    $enabled = Craft::parseEnv($settings->enabledCookieBar);
    if($enabled == 1){
      $enabled = true;
    }else{
      $enabled = false;
    }

    $consentEnabled = Craft::parseEnv($settings->googleConsentV2Enabled);
    if($consentEnabled == 1){
      $consentEnabled = true;
    }else{
      $consentEnabled = false;
    }

    if (!$enabled){
      return '';
    }

    $permissions = CookieMng::$instance->services->getPermissionCookie();
    $permissions = $permissions ? $permissions : '';
    return Craft::$app->view->renderTemplate('cookiemng/panel/bar.twig',['enabled'=>$enabled,'consentEnabled'=>$consentEnabled,'settings'=>$settings,'permissions'=>$permissions ? explode(',',$permissions) : false],View::TEMPLATE_MODE_CP);
  }

  #TWIG => {{ craft.cookiemng.consentTemplate()|raw }}
  public function consentTemplate()
  {
    Craft::$app->view->registerAssetBundle(PluginAssets::class);
    $settings = CookieMng::$instance->getSettings();

    $enabled = Craft::parseEnv($settings->enabledCookieBar);
    if($enabled == 1){
      $enabled = true;
    }else{
      $enabled = false;
    }

    $consentEnabled = Craft::parseEnv($settings->googleConsentV2Enabled);
    if($consentEnabled == 1){
      $consentEnabled = true;
    }else{
      $consentEnabled = false;
    }

    if (!$enabled){
      return '';
    }
    
    
    $permissions = CookieMng::$instance->services->getPermissionCookie();
    $permissions = $permissions ? $permissions : '';
    if($consentEnabled){
      return Craft::$app->view->renderTemplate('cookiemng/panel/consentTemplateV2.twig',['enabled'=>$enabled,'consentEnabled'=>$consentEnabled,'settings'=>$settings,'permissions'=>explode(',',$permissions)],View::TEMPLATE_MODE_CP);
    }else{
      return '';
    }
  }
}
