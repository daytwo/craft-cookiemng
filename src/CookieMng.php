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

    public function getEnvValues(): object
    {
        $env = (object)[
            'cookieEnabled' => Craft::parseEnv('CM_COOKIE_ENABLED') !== 1 ? true: true,
            'cookieName' => Craft::parseEnv('CM_COOKIE_NAME') === 'CM_COOKIE_NAME' ? 'craft_daytwo_cookiemng': Craft::parseEnv('CM_COOKIE_NAME'),
            'cookieDomain' => Craft::parseEnv('CM_COOKIE_DOMAIN') === 'CM_COOKIE_DOMAIN' ? '' : Craft::parseEnv('CM_COOKIE_DOMAIN'),
            'cookieExpiry' => Craft::parseEnv('CM_COOKIE_EXPIRY') === 'CM_COOKIE_EXPIRY' ? 365 : Craft::parseEnv('CM_COOKIE_EXPIRY'),
            'cookiePath' => Craft::parseEnv('CM_COOKIE_PATH') === 'CM_COOKIE_PATH' ? '/' : Craft::parseEnv('CM_COOKIE_PATH'),
            'cookieSecure' => Craft::parseEnv('CM_COOKIE_SECURE') === 'CM_COOKIE_SECURE' ? false : true,
            'cookieGoogleEnabled' => Craft::parseEnv('CM_COOKIE_GOOGLE_ENABLED') === 'CM_COOKIE_GOOGLE_ENABLED' ? false : true
        ];

        return $env;
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        $settings = $this->getSettings();
        $env = $this->getEnvValues();
        
        return Craft::$app->view->renderTemplate('cookiemng/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
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
