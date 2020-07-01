<?php
abstract class _IndexController extends Base_Controller implements iController
{
    protected function ProcessApp($app_info)
    {
        if(count($app_info["uri_parts"]) == 0)
            $app_info["controller_name"] = "Index";
        else $app_info["controller_name"] = array_shift($app_info["uri_parts"]);

        define("ACTIVE_VIEW", $app_info["controller_name"]);
        $app_info["controller_name"] .= "Controller";

        if($app_info["controller_name"] == "IndexController") return $app_info;
        else
        {
            require(ACTIVE_APP_DIR . '/' . $app_info["controller_name"] . ".php");
            (new $app_info["controller_name"])->Index($app_info);
        }
    }
}