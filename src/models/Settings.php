<?php

namespace daytwo\cookiemng\models;

use Craft;
use craft\base\Model;

/**
 * daytwo/craft-cookiemng settings
 */
class Settings extends Model
{
    
    public $cookieEnabled = false;
    public $cookieName = 'craft_daytwo_cookiemng';
    public $cookieDomain = '';
    public $cookieExpiry = 365;
    public $cookiePath = '/';
    public $cookieSecure = false;
    public $cookieGoogleEnabled = false;

    public function getCookieEnabled(bool $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookieEnabled, $siteHandle);
    }
    public function getCookieName(string $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookieName, $siteHandle);
    }
    public function getCookieDomain(string $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookieDomain, $siteHandle);
    }
    public function getCookieExpiry(int $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookieExpiry, $siteHandle);
    }
    public function getCookiePath(string $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookiePath, $siteHandle);
    }
    public function getCookieSecure(bool $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookieSecure, $siteHandle);
    }
    public function getCookieGoogleEnabled(bool $siteHandle = null){
        return \craft\helpers\ConfigHelper::localizedValue($this->cookieGoogleEnabled, $siteHandle);
    }
    public $theme = "blue";
    public $inverted = false;
    public $cornerPanel = false;
    public $zIndex = 999999;
    public $blockBackground = false;
    public function getTheme(string $siteHandle = null) {   
        $allowed = ['blue', 'green', 'red', 'yellow', 'purple', 'black'];
        $color = \craft\helpers\ConfigHelper::localizedValue($this->theme, $siteHandle);
        if(in_array($color, $allowed)){
            return $color;
        }else{
            return 'blue';
        }
    }
    public function getInverted(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->inverted, $siteHandle);
    }
    public function getCornerPanel(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->cornerPanel, $siteHandle);
    }
    public function getZIndex(int $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->zIndex, $siteHandle);
    }
    public function getBlockBackground(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->blockBackground, $siteHandle);
    }

    public $saveButton = 'Save';
    public $acceptAllButton = 'Accept all';
    public $acceptFunctionalButton = 'Accept functional';
    public $denyAllButton = 'Deny all';
    public $closeButton = 'Dismiss';
    public $customizeButton = 'Customize';
    public function getSaveButton(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->saveButton, $siteHandle);
    }
    public function getAcceptAllButton(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->acceptAllButton, $siteHandle);
    }
    public function getAcceptFunctionalButton(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->acceptFunctionalButton, $siteHandle);
    }
    public function getDenyAllButton(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->denyAllButton, $siteHandle);
    }
    public function getCloseButton(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->closeButton, $siteHandle);
    }
    public function getCustomizeButton(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->customizeButton, $siteHandle);
    }

    public $cookiesHeader = 'Information about cookies';
    public $cookiesDescription = 'This website uses cookies. We use cookies primarily to improve and analyze the experience of our website and for marketing purposes. Because we respect your privacy rights, you can choose not to accept certain types of cookies. Click on the different category headings to find out more and to change the default settings. Blocking some types of cookies may have a negative impact on your experience of the website and may limit the services we can provide to you.';
    public $cookiesReadMore = 'Read more';
    public $cookiesReadMoreLink = '';
    public function getCookiesHeader(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->cookiesHeader, $siteHandle);
    }
    public function getCookiesDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->cookiesDescription, $siteHandle);
    }
    public function getCookiesReadMore(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->cookiesReadMore, $siteHandle);
    }
    public function getCookiesReadMoreLink(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->cookiesReadMoreLink, $siteHandle);
    }

    public $functionalCookies = true;
    public $functionalTitle = 'Functional cookies 1';
    public $functionalDescription = 'Functional cookies are necessary for the website to function properly.';   
    public function getFunctionalCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->functionalCookies, $siteHandle);
    }
    public function getFunctionalTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->functionalTitle, $siteHandle);
    }
    public function getFunctionalDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->functionalDescription, $siteHandle);
    }

    public $analyticsCookies = true;
    public $analyticsTitle = 'Analytics cookies';   
    public $analyticsDescription = 'Analytics cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.';   
    public function getAnalyticsCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->analyticsCookies, $siteHandle);
    }
    public function getAnalyticsTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->analyticsTitle, $siteHandle);
    }
    public function getAnalyticsDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->analyticsDescription, $siteHandle);
    }

    public $advertisingCookies = true;
    public $advertisingTitle = 'Advertising cookies';   
    public $advertisingDescription = 'Advertising cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user.';   
    public function getAdvertisingCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->advertisingCookies, $siteHandle);
    }
    public function getAdvertisingTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->advertisingTitle, $siteHandle);
    }
    public function getAdvertisingDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->advertisingDescription, $siteHandle);
    }

    public $personalizationCookies = true;
    public $personalizationTitle = 'Personalization cookies';   
    public $personalizationDescription = 'Personalization cookies are used to track visitors across websites. The intention is to display content/ads that are relevant and engaging for the individual user.';   
    public function getPersonalizationCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->personalizationCookies, $siteHandle);
    }
    public function getPersonalizationTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->personalizationTitle, $siteHandle);
    }
    public function getPersonalizationDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->personalizationDescription, $siteHandle);
    }

    public $extraCookies = false;
    public $extraCookieProperty;
    public $extraTitle;
    public $extraDescription;
    public function getExtraCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->extraCookies, $siteHandle);
    }
    public function getExtraCookieProperty(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->extraCookieProperty, $siteHandle);
    }
    public function getExtraTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->extraTitle, $siteHandle);
    }
    public function getExtraDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->extraDescription, $siteHandle);
    }
}
