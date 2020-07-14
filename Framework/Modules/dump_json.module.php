<?php

function dump_json($what, bool $exit = true)
{
    $what = json_encode($what);
    echo "<script>console.log($what)</script>";
    if($exit)
        exit();
}