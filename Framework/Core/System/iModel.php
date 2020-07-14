<?php

/**
 * Interface iModel
 * All the models should implement this interface.
 */
interface iModel
{
    public function GetData($params = []);
}