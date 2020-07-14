<?php

function dump($what, bool $exit = true)
{
    echo "<pre>";
	var_dump($what);
    echo "</pre>";
    if($exit)
        exit();
}