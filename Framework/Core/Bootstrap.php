<?php

/**
 * Class MVCEngine
 * MVC MAGIC IS HERE
 */
class MVCEngine
{
    /**
     * @var - contains URI information
     */
    /**
     * @var - contains App information
     */
    private
        $Uri,
        $App;

    /**
     * MVCEngine constructor.
     * @param array $Routes
     */
    public function __construct(array $Routes)
    {
        $this->ProcessGetUri($Routes['Redirects']);
        $this->ProcessPath($Routes['Routes']);
        $this->ProcessModels();
        $this->ProcessControllers();
    }

    /**
     * @param array $Redirects
     * handling redirects and processing URI
     */
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

    /**
     * @param array $Routes
     * processing routes
     */
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

        $this->App['Name'] = array_shift($this->Uri);
        $this->App['UriParts'] = [];

        $firstParameterFound = false;
        foreach($this->Uri as $Parameter)
        {
            if(substr_count($Parameter, "=") == 0 && !$firstParameterFound)
            {
                $this->App['UriParts'][] = $Parameter;
                continue;
            }

            $eqPos = stripos($Parameter, "=");
            if($eqPos)
            {
                $Key = substr($Parameter, 0, $eqPos);
                $Value = substr($Parameter, $eqPos+1);
                if($Value == "") $Value = true;
                $this->App['Args'][$Key] = urldecode($Value);
                $firstParameterFound = true;
            }
        }
    }

    /**
     * requiring all the models in models folder.
     */
    private function ProcessModels()
    {
        $models = glob(MODELS_DIR . '/*/*Model.php');
        foreach ($models as $model) {
            require $model;
        }
    }

    /**
     * checking if controller exists and, if it is, it requires it. Else it will require 404/IndexController. Notice that 404/IndexController should exists, if it isn't, you will get an exception.
     */
    private function ProcessControllers()
    {
        if(!file_exists(APPS_DIR . '/' . $this->App['Name'] . '/IndexController.php'))
        {
            $this->App['Args'] = $this->App['Name'];
            $this->App['Name'] = '404';
            $this->App['Controller'] = 'IndexController';
        }

        define("ACTIVE_APP", $this->App['Name']);
        define("ACTIVE_APP_DIR", APPS_DIR . '/' . ACTIVE_APP);

        require APPS_DIR . '/' . $this->App['Name'] . '/IndexController.php';
        $indexControllerObj = new IndexController();
        $indexControllerObj->ProcessApp($indexControllerObj, $this->App);
    }
}