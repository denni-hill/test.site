<?php

class MVCEngine
{
    private
        $Uri,
        $App;

    public function __construct(array $Routes)
    {
        $this->ProcessGetUri($Routes['redirects']);
        $this->ProcessPath($Routes['routes']);
        $this->ProcessControllersAndModels();
    }

    private function ProcessGetUri(array $Redirects)
    {
        $this->Uri = $_SERVER['REQUEST_URI'];
        $this->Uri = trim($this->Uri, '/');
        $this->TrimmedUri = $this->Uri;
        $this->Uri = explode('/', $this->Uri);

        foreach($Redirects as $From => $To)
        {
            if($this->TrimmedUri == trim($From, '/'))
                moveTo($To);
        }
    }

    private function ProcessPath(array $Routes)
    {
        foreach($Routes as $ShortUri => $FullUri)
        {
            if($this->Uri[0] == $ShortUri)
            {
                array_shift($this->Uri);

                $FullUri = trim($FullUri);
                $FullUri = explode('/', $FullUri);
                $this->Uri = array_merge($FullUri, $this->Uri);
                break;
            }
        }

        $this->App['name'] = array_shift($this->Uri);
        $this->App['uri_parts'] = [];

        $first_parameter_found = false;
        foreach($this->Uri as $Parameter)
        {
            if(substr_count($Parameter, "=") == 0 && !$first_parameter_found)
            {
                $this->App['uri_parts'][] = $Parameter;
                continue;
            }
            $Key = explode("=", $Parameter)[0];
            $Value = join("=", array_splice(explode("=", $Parameter), 1, 1));
            if($Value == "") $Value = true;
            $this->App['args'][$Key] = urldecode($Value);
            $first_parameter_found = true;
        }
    }

    private function ProcessControllersAndModels()
    {
        if(!file_exists(CONTROLLERS_DIR . '/' . $this->App['name'] . '/IndexController.php'))
        {
            $this->App['args'] = $this->App['name'];
            $this->App['name'] = '404';
            $this->App['controller'] = 'IndexController';
        }

        define("ACTIVE_APP", $this->App['name']);
        define("ACTIVE_APP_DIR", CONTROLLERS_DIR . '/' . $this->App['name']);

        $models = glob(MODELS_DIR . '/*/*Model.php');
        foreach ($models as $model) {
            require $model;
        }

        require CONTROLLERS_DIR . '/' . $this->App['name'] . '/IndexController.php';
        $controller_obj = new IndexController();
        $controller_obj->ProcessApp($controller_obj, $this->App);
    }
}