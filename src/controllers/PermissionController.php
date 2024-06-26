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

use daytwo\cookiemng\CookieMng;

/**
* Class PermissionController
*
* @author    Daytwo
* @package   cookiemng
* @since     1.0.0
*
*/

class PermissionController extends Controller
{

    //only allow logged in calls to all modules
    //protected bool|array|int $allowAnonymous = false;
    
    //allow non-looged in calls to all methods
    //protected bool|array|int $allowAnonymous = true;

    //allow non-looged in calls to specific methods
    protected bool|array|int $allowAnonymous = ['example','set'];

    #In case you need to remove CSRF valiation
    protected $skipCSRF = ['example','set']; 

    public function beforeAction($default):bool
    {
        $actions = Craft::$app->getRequest()->actionSegments;
        $action = end($actions);
        if(in_array($action, $this->skipCSRF)){
          $this->enableCsrfValidation = false;
        }
        
        return parent::beforeAction($default);
    }

    public function actionExample()
    {
        //in case you only want to allow post requests
        //$this->requirePostRequest();
        $request = Craft::$app->getRequest();

        $redirect = $request->getParam('redirect', '');
        $sample = $request->getParam('sample', 'example method via PermissionController');

        $result = CookieMng::$instance->services->exampleService($sample);

        // if this was an ajax request, return json
        if ($request->getAcceptsJson()) {
            return $this->asJson($result);
        }

        // if a redirect variable was passed, do redirect
        if ($redirect && $redirect !== '') {
            return $this->redirectToPostedUrl(array('cookiemng' => $result));
        }

        // set route variables and return
        Craft::$app->getUrlManager()->setRouteParams([
            'variables' => ['cookiemng' => $result]
        ]);

        return $this->asJson($result);
    }
    public function actionSet()
    {
        //in case you only want to allow post requests
        //$this->requirePostRequest();
        $request = Craft::$app->getRequest();

        $redirect = $request->getParam('redirect', '');
        $permissions = $request->getParam('permissions');
        $siteHandle = $request->getParam('siteHandle');

        $result = CookieMng::$instance->services->setPermissionCookie($permissions,$siteHandle);

        // if this was an ajax request, return json
        return $this->asJson($permissions);
        if ($request->getAcceptsJson()) {
            return $this->asJson($result);
        }

        // if a redirect variable was passed, do redirect
        if ($redirect && $redirect !== '') {
            return $this->redirectToPostedUrl(array('cookiemng' => $result));
        }

        // set route variables and return
        Craft::$app->getUrlManager()->setRouteParams([
            'variables' => ['cookiemng' => $result]
        ]);

        return $this->asJson($result);
    }

}
