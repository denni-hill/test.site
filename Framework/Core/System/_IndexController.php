<?php

/**
 * Class _IndexController
 * all the IndexControllers (root controllers of each app) should extend this class. It already extends Base_Controller class and realises function to route requests inside the app,
 * so you can override ProcessApp method in your IndexController and make your app more flexible.
 */
abstract class _IndexController extends Base_Controller
{
    public function ProcessApp(_IndexController $controllerObj, $appInfo)
    {
        $callableMethodName = "Index";
        if (count($appInfo["UriParts"]) == 0)
            $appInfo["ControllerName"] = "Index";
        else $appInfo["ControllerName"] = array_shift($appInfo["UriParts"]);

        if (strtolower($appInfo["ControllerName"]) == "api") {
            $callableMethodName = "ApiIndex";
            if (count($appInfo["UriParts"]) == 0)
                $appInfo["ControllerName"] = "Index";
            else $appInfo["ControllerName"] = array_shift($appInfo["UriParts"]);
        }

        define("ACTIVE_VIEW", $appInfo["ControllerName"]);
        $appInfo["ControllerName"] .= "Controller";

        if(strtolower($appInfo["ControllerName"]) == "indexcontroller")
        {
            if(method_exists($controllerObj, $callableMethodName))
            {
                $controllerObj->$callableMethodName($appInfo);
            }
            else moveTo('/404/');
        }
        else
        {
            if(file_exists(ACTIVE_APP_DIR . '/' . $appInfo["ControllerName"] . ".php"))
            {
                require(ACTIVE_APP_DIR . '/' . $appInfo["ControllerName"] . ".php");
                $controllerObj = new $appInfo["ControllerName"]();
                if(method_exists($controllerObj, $callableMethodName))
                {
                    $controllerObj->$callableMethodName($appInfo);
                }
                else moveTo('/404/');
            }
            else moveTo('/404/');
        }
    }
}