<?php

class AjaxResponder
{
    private $Response = array(
        'Errors' => array(),
        'Messages' => array(),
        'Arguments' => array(),
        'ConsoleLogs' => array(),
        'Redirect' => "",
    );

    public function AddError($value)
    {
        $this->Response['Errors'][] = $value;
    }

    public function AddMessage($value)
    {
        $this->Response['Messages'][] = $value;
    }

    public function AddArgument($value)
    {
        $this->Response['Arguments'][] = $value;
    }

    public function AddRedirect($value)
    {
        $this->Response['Redirect'] = $value;
    }

    public function AddConsoleLog($value)
    {
        $this->Response["ConsoleLogs"][] = $value;
    }

    public function GetServerAnswer()
    {
        return $this->Response;
    }

    public function GetJsonServerAnswer()
    {
        return json_encode($this->Response);
    }
}