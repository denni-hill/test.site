<?php

/**
 * Class Base_Controller
 * all the non-root controllers (not IndexController class) should extend this class. you can put here some functions or variables that you may want to use in whole site.
 */
abstract class Base_Controller
{
    protected $AjaxResponder;

    protected $DataBaseProvider;

    public function __construct(AjaxResponder $AjaxResponder = NULL, DataBaseProvider $DataBaseProvider = NULL)
    {
        $this->AjaxResponder = $AjaxResponder == NULL ? new AjaxResponder() : $AjaxResponder;
        $this->DataBaseProvider = $DataBaseProvider == NULL ? new DataBaseProvider($this->AjaxResponder) : $DataBaseProvider;
    }
}