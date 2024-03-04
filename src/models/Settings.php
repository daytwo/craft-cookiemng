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
    public $cookieExpiry = 365;
    public $cookiePath = "/";
    public $cookieSecure = true;

    public $googleConsentV2Enabled = true;

    
    public $theme = "blue";
    public $inverted = false;
    public $zIndex = 999999;
    public $blockBackground = false;

    public $saveButton = 'Save';
    public $acceptAllButton = 'Accept all';
    public $acceptFunctionalButton = 'Accept functional';
    public $denyAllButton = 'Deny all';
    public $closeButton = 'Dismiss';
    public $customizeButton = 'Customize';

    public $cookiesHeader = 'Information about cookies';
    public $cookiesDescription = 'This website uses cookies. We use cookies primarily to improve and analyze the experience of our website and for marketing purposes. Because we respect your privacy rights, you can choose not to accept certain types of cookies. Click on the different category headings to find out more and to change the default settings. Blocking some types of cookies may have a negative impact on your experience of the website and may limit the services we can provide to you.';

    public $functionalCookies = true;
    public $functionalTitle = 'Functional cookies 1';
    public $functionalDescription = 'Functional cookies are necessary for the website to function properly.';   
    
    public $analyticsCookies = true;
    public $analyticsTitle = 'Analytics cookies';   
    public $analyticsDescription = 'Analytics cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.';   

    public $advertisingCookies = true;
    public $advertisingTitle = 'Advertising cookies';   
    public $advertisingDescription = 'Advertising cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user.';   

    public $personalizationCookies = true;
    public $personalizationTitle = 'Personalization cookies';   
    public $personalizationDescription = 'Personalization cookies are used to track visitors across websites. The intention is to display content/ads that are relevant and engaging for the individual user.';   

    public $extraCookies = false;
    public $extraCookieProperty;
    public $extraTitle;
    public $extraDescription;
}
