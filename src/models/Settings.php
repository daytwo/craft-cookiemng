<?php

namespace daytwo\cookiemng\models;

use Craft;
use craft\base\Model;

/**
 * daytwo/craft-cookiemng settings
 */
class Settings extends Model
{
    public $cookieName = 'craft_cookiemng';
    public $cookieDomain;

    public $theme = "blue";
    public $inverted = false;

    public $saveButton = 'Save';
    public $acceptAllButton = 'Accept all';
    public $denyAllButton = 'Deny all';
    public $closeButton = 'Dismiss';
    public $customizeButton = 'Customize';

    public $functionalCookies = true;
    public $functionalTitle = 'Functional cookies 1';
    public $functionalDescription = 'Functional cookies are necessary for the website to function properly.';   
    
    public $analyticsCookies = true;
    public $analyticsTitle = 'Analytics cookies';   
    public $analyticsDescription = 'Analytics cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.';   

    public $advertisingCookies = true;
    public $advertisingTitle = 'Advertising cookies';   
    public $advertisingDescription = 'Advertising cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user.';   

    public $customizationCookies = true;
    public $customizationTitle = 'Customization cookies';   
    public $customizationDescription = 'Customization cookies are used to track visitors across websites. The intention is to display content/ads that are relevant and engaging for the individual user.';   

    public $extraCookies = false;
    public $extraCookieProperty;
    public $extraTitle;
    public $extraDescription;
}
