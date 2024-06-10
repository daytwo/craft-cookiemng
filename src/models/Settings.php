<?php

namespace daytwo\cookiemng\models;

use Craft;
use craft\base\Model;

/**
 * daytwo/craft-cookiemng settings
 */
class Settings extends Model
{
    
    public $cookieEnabled;
    public $cookieName;
    public $cookieDomain;
    public $cookieExpiry;
    public $cookiePath;
    public $cookieSecure;
    public $cookieGoogleEnabled;

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
    public $theme;
    public $inverted;
    public $cornerPanel;
    public $zIndex;
    public $blockBackground;
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

    public $saveButton;
    public $acceptAllButton;
    public $acceptFunctionalButton;
    public $denyAllButton;
    public $closeButton;
    public $customizeButton;
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

    public $cookiesHeader;
    public $cookiesDescription;
    public $cookiesReadMore;
    public $cookiesReadMoreLink;
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

    public $functionalCookies;
    public $functionalTitle;
    public $functionalDescription;
    public function getFunctionalCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->functionalCookies, $siteHandle);
    }
    public function getFunctionalTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->functionalTitle, $siteHandle);
    }
    public function getFunctionalDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->functionalDescription, $siteHandle);
    }

    public $analyticsCookies;
    public $analyticsTitle;
    public $analyticsDescription;
    public function getAnalyticsCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->analyticsCookies, $siteHandle);
    }
    public function getAnalyticsTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->analyticsTitle, $siteHandle);
    }
    public function getAnalyticsDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->analyticsDescription, $siteHandle);
    }

    public $advertisingCookies;
    public $advertisingTitle;
    public $advertisingDescription;
    public function getAdvertisingCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->advertisingCookies, $siteHandle);
    }
    public function getAdvertisingTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->advertisingTitle, $siteHandle);
    }
    public function getAdvertisingDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->advertisingDescription, $siteHandle);
    }

    public $personalizationCookies;
    public $personalizationTitle;
    public $personalizationDescription;
    public function getPersonalizationCookies(bool $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->personalizationCookies, $siteHandle);
    }
    public function getPersonalizationTitle(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->personalizationTitle, $siteHandle);
    }
    public function getPersonalizationDescription(string $siteHandle = null) {
        return \craft\helpers\ConfigHelper::localizedValue($this->personalizationDescription, $siteHandle);
    }

    public $extraCookies;
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
