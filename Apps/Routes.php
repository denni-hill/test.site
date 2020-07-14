<?php

//ROUTES WORK NEXT WAY: 'Alias' => 'AppName/ControllerName/param1=123/param2=321'. FRAMEWORK WILL REDIRECT REQUEST TO AppName/ControllerName LIKE IT WAS SENT DIRECTLY THERE AND ADD GIVEN HERE PARAMS TO REQUEST.
//REDIRECTS WORK NEXT WAY: 'Alias' => 'AppName/ControllerName/param1=123/param2=321'. FRAMEWORK WILL REDIRECT REQUEST TO AppName/ControllerName. IF Alias == REQUEST URL, THEN REDIRECTION WILL SUCCEED. IT WILL NOT SEND ANY REQUEST PARAMETERS YOU HAD IN YOUR REQUEST. ONLY PARAMETERS SPECIFIED HERE.

return array(
    'Routes' =>
    [

    ],
    'Redirects' =>
    [
        '' => 'Home/Index'
    ]
);