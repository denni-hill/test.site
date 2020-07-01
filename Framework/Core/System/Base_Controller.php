<?php
abstract class Base_Controller
{
    private $Answer = array(
        'Errors' => array(),
        'Messages' => array(),
        'Arguments' => array(),
        'ConsoleLogs' => array(),
        'Redirect' => "",
    );

    public function AddError($value)
    {
        $this->Answer['Errors'][] = $value;
    }

    public function AddMessage($value)
    {
        $this->Answer['Messages'][] = $value;
    }

    public function AddArgument($value)
    {
        $this->Answer['Arguments'][] = $value;
    }

    public function AddRedirect($value)
    {
        $this->Answer['Redirect'] = $value;
    }

    public function AddConsoleLog($value)
    {
        $this->Answer["ConsoleLogs"][] = $value;
    }

    public function GetServerAnswer()
    {
        return $this->Answer;
    }

    public function GetJsonServerAnswer()
    {
        return json_encode($this->Answer);
    }
}