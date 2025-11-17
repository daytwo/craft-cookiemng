<?php

namespace daytwo\cookiemng\controllers;

/**
* Cookie Management module for Craft CMS 4.x
*
*
* @link      https://daytwo.no
* @copyright Copyright (c) 2024 Daytwo
*/

use Craft;
use craft\web\Controller;
use craft\web\View;

use daytwo\cookiemng\CookieMng;

/**
* Class PanelController
*
* Handles async cookie panel rendering to bypass CDN caching
*
* @author    Daytwo
* @package   cookiemng
* @since     2.0.0
*
*/

class PanelController extends Controller
{
    // Allow non-logged in calls to get-panel method
    protected bool|array|int $allowAnonymous = ['get-panel'];

    // Skip CSRF validation for async requests
    protected $skipCSRF = ['get-panel']; 

    public function beforeAction($default): bool
    {
        $actions = Craft::$app->getRequest()->actionSegments;
        $action = end($actions);
        if(in_array($action, $this->skipCSRF)){
          $this->enableCsrfValidation = false;
        }
        
        return parent::beforeAction($default);
    }

    /**
     * Returns the cookie panel HTML asynchronously
     * Sets proper cache-control headers to prevent CDN caching
     * 
     * @return \yii\web\Response
     */
    public function actionGetPanel()
    {
        $request = Craft::$app->getRequest();
        
        // Get site handle from request
        $siteHandle = $request->getParam('siteHandle', 'default');
        $deactivated = $request->getParam('deactivated', false);
        
        // Set cache-control headers to prevent caching
        Craft::$app->getResponse()->getHeaders()->set('Cache-Control', 'private, no-store, no-cache, must-revalidate');
        Craft::$app->getResponse()->getHeaders()->set('Pragma', 'no-cache');
        Craft::$app->getResponse()->getHeaders()->set('Expires', '0');
        
        $settings = CookieMng::$instance->getSettings();
        
        if (!$settings->getCookieEnabled($siteHandle)){
            return $this->asJson([
                'success' => true,
                'enabled' => false,
                'html' => ''
            ]);
        }
        
        // Get current permissions from cookie
        $permissions = CookieMng::$instance->services->getPermissionCookie($siteHandle);
        $permissions = $permissions ? $permissions : '';
        
        // Render the panel HTML
        $html = Craft::$app->view->renderTemplate(
            'cookiemng/panel/bar.twig',
            [
                'settings' => $settings,
                'permissions' => $permissions ? explode(',', $permissions) : false,
                'siteHandle' => $siteHandle,
                'deactivated' => $deactivated
            ],
            View::TEMPLATE_MODE_CP
        );
        
        // Also render consent template script
        $consentScript = Craft::$app->view->renderTemplate(
            'cookiemng/panel/consentTemplateV2.twig',
            [
                'settings' => $settings,
                'permissions' => explode(',', $permissions),
                'siteHandle' => $siteHandle
            ],
            View::TEMPLATE_MODE_CP
        );
        
        return $this->asJson([
            'success' => true,
            'enabled' => true,
            'html' => $html,
            'consentScript' => $consentScript,
            'hasConsent' => !empty($permissions)
        ]);
    }
}
