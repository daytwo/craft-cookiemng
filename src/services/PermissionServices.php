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

    public function setPermissionCookie($value,$siteHandle = "default")
    {        
        $settings = CookieMng::$instance->getSettings();
        //$env = CookieMng::$instance->getEnvValues();

        return setcookie(
            $settings->getCookieName($siteHandle),
            $value, 
            time() + 60 * 60 * 24 * $settings->getCookieExpiry($siteHandle),
            $settings->getCookiePath($siteHandle),
            $settings->getCookieDomain($siteHandle),
            $settings->getCookieSecure($siteHandle),
            true
        );
    }
    public function getPermissionCookie($siteHandle = "default")
    {
        $settings = CookieMng::$instance->getSettings();
        //$env = CookieMng::$instance->getEnvValues();

        if(array_key_exists($settings->getCookieName($siteHandle),$_COOKIE)){
            return $_COOKIE[$settings->getCookieName($siteHandle)];
        }
        return false;
    }
}