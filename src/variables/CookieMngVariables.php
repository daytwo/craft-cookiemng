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
    return CookieMng::$instance->services->setPermissionCookie($value, $duration, $secure, $http_only);
  }

  #TWIG => {{ craft.cookiemng.getPermissionCookie($name) }}
  public function getPermissionCookie()
  {
    return CookieMng::$instance->services->getPermissionCookie($this->cookieName);
  }
}
