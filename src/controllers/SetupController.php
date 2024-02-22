<?php


namespace daytwo\cookiemng\controllers;
use Craft;
use craft\web\Controller;

use daytwo\cookiemng\models\Setup;
use craft\helpers\DateTimeHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use daytwo\cookiemng\CookieMng;


class SetupController extends Controller
{
    public function actionEdit()
    {
        $this->requireAdmin();

        // Ensure the user has permission to save events
        $settings = CookieMng::$instance->getSettings();

        
        return $this->renderTemplate('cookiemng/setup/index', [
            'settings' => $settings,
        ]);
    }

    public function actionSave()
    {
        $this->requireAdmin();

        $this->requirePostRequest();
        $settings = CookieMng::$instance->getSettings();
        $request = Craft::$app->getRequest();

        // Populate settings model with POST data
        $settings->setAttributes($request->getBodyParams(),false);

        // Validate and save settings
        if ($settings->validate() && Craft::$app->plugins->savePluginSettings(CookieMng::$instance, $settings->getAttributes())) {
            Craft::$app->getSession()->setNotice('Settings saved.');
            return $this->redirectToPostedUrl();
        } else {
            Craft::$app->getSession()->setError('Error saving settings.');
        }
    }
}