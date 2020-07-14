<?php
class IndexController extends _IndexController implements iController
{
    function Index(array $app_info)
    {
        View(["TODO_data" => (new TODOModel())->GetData($app_info["Args"])]);
    }
}