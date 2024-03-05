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

    public function setPermissionCookie($value, $duration, $secure=true, $http_only=true)
    {        
       

        $settings = CookieMng::$instance->getSettings();
        $cookieName = $settings->cookieName;
        $domain = $settings->cookieDomain;
        if(!$cookieName || $cookieName == '$COOKIE_NAME'){
            $domain = 'craft_daytwo_cookiemng';
        }
        if(!$domain || $domain == '$COOKIE_DOMAIN'){
            $domain = null;
        }

        if(str_contains($domain, 'localhost')){
            $secure = false;
        }

        return setcookie($cookieName, $value, time() + 60 * 60 * 24 * $duration, '/', $domain, $secure, $http_only);
    }
    public function getPermissionCookie()
    {   
        $settings = CookieMng::$instance->getSettings();
        $cookieName = $settings->cookieName;
        if(!$cookieName || $cookieName == '$COOKIE_NAME'){
            $domain = 'craft_daytwo_cookiemng';
        }

        if(array_key_exists($cookieName,$_COOKIE)){
            return $_COOKIE[$cookieName];
        }
        return false;
    }
}