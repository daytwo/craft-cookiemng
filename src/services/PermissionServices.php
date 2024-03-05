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
        $env = CookieMng::$instance->getEnvValues();

        return setcookie($env->cookieName, $value, time() + 60 * 60 * 24 * $env->cookieExpiry, $env->cookiePath, $env->cookieDomain, $env->cookieSecure, true);
    }
    public function getPermissionCookie()
    {
        $settings = CookieMng::$instance->getSettings();
        $env = CookieMng::$instance->getEnvValues();

        if(array_key_exists($env->cookieName,$_COOKIE)){
            return $_COOKIE[$env->cookieName];
        }
        return false;
    }
}