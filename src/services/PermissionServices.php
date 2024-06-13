<?php

namespace daytwo\cookiemng\services;

/**
* Cookie Management module for Craft CMS 4.x
*
*
* @link      https://daytwo.no
* @copyright Copyright (c) 2024 Daytwo
*/


use Craft;
use craft\base\Component;
use craft\base\Element;

use daytwo\cookiemng\CookieMng;

/**
* Class PermissionServices
*
* @author    Daytwo
* @package   cookiemng
* @since     1.0.0
*
*/

class PermissionServices extends Component{

    public function setPermissionCookie($value)
    {        
        $settings = CookieMng::$instance->getSettings();
        //$env = CookieMng::$instance->getEnvValues();

        return setcookie(
            $settings->getCookieName(Craft::$app->getSites()->currentSite->handle),
            $value, 
            time() + 60 * 60 * 24 * $settings->getCookieExpiry(Craft::$app->getSites()->currentSite->handle),
            $settings->getCookiePath(Craft::$app->getSites()->currentSite->handle),
            $settings->getCookieDomain(Craft::$app->getSites()->currentSite->handle),
            $settings->getCookieSecure(Craft::$app->getSites()->currentSite->handle),
            true
        );
    }
    public function getPermissionCookie()
    {
        $settings = CookieMng::$instance->getSettings();
        //$env = CookieMng::$instance->getEnvValues();

        if(array_key_exists($settings->getCookieName(Craft::$app->getSites()->currentSite->handle),$_COOKIE)){
            return $_COOKIE[$settings->getCookieName(Craft::$app->getSites()->currentSite->handle)];
        }
        return false;
    }
}