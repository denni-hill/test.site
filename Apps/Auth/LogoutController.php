<?php
class LogoutController extends Base_Controller implements iController
{
    public function Index(array $app_info)
    {
        unset($_SESSION['logged_user']);
        moveTo("/");
    }
}