<?php
abstract class Base_Model implements iModel
{
    protected $Responder;

    protected $DataBaseProvider;

    public function __construct(AjaxResponder $Responder = NULL, DataBaseProvider $DataBaseProvider = NULL)
    {
        $this->Responder = $Responder == NULL ? new AjaxResponder() : $Responder;
        $this->DataBaseProvider = $DataBaseProvider == NULL ? new DataBaseProvider($Responder) : $DataBaseProvider;
    }

    public function GetResponse()
    {
        return $this->Responder->GetServerAnswer();
    }
}