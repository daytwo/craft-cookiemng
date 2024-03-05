<?php

namespace daytwo\cookiemng;

use Craft;

use daytwo\cookiemng\models\Settings;
use daytwo\cookiemng\services\PermissionServices;
use daytwo\cookiemng\variables\CookieMngVariables;

use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\CraftVariable;
use craft\web\twig\variables\Cp;
use craft\web\UrlManager;

use yii\base\Event;

/**
 * daytwo/craft-cookiemng plugin
 *
 * @method static CookieMng getInstance()
 * @method Settings getSettings()
 * @author Daytwo <tech@daytwo.no>
 * @copyright Daytwo
 * @license https://craftcms.github.io/license/ Craft License
 */
class CookieMng extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    public static $instance;
    public static $plugin;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {

        // Set instance of this module
        self::$instance = $this;
        self::$plugin = $this;

        // Set alias for this module
        Craft::setAlias('@daytwo/cookiemng', __DIR__);
        $this->controllerNamespace = 'daytwo\cookiemng\controllers';

        parent::init();

        // Register services
        $this->setComponents([
        'services' => PermissionServices::class
        ]);
        
        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
        });

    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        $settings = $this->getSettings();
    
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
        
        return Craft::$app->view->renderTemplate('cookiemng/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
            'enabled' => $enabled,
            'consentEnabled' => $consentEnabled

        ]);
    }
    
    public function getCpNavItem(): ?array
    {
        $navItem = parent::getCpNavItem();
        $navItem['label'] = "Cookie Manager";

        return $navItem;
    }

    private function attachEventHandlers(): void
    {
        // Register variables
        Event::on(
        CraftVariable::class,
        CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('cookiemng', CookieMngVariables::class);
            }
        );

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, [
                'cookiemng' => 'cookiemng/setup/edit'
            ]);
        });
    }
}
