<?php
class IndexController extends _IndexController implements iController
{
    function Index(array $app_info)
    {
        if(strtolower($app_info["uri_parts"][1]) == "logout")
        {
            unset($_SESSION['logged_user']);
            moveTo("/");
        }

        if(isset($_SESSION["logged_user"]))
        {
            moveTo("/");
            return;
        }

        if(!empty($_POST))
        {
            if(isset($_POST["hash"]))
            {
                $user = $this->GetUserByHash($_POST["hash"]);
                if(!$user)
                    $this->AddError("You entered wrong data!");
                else
                {
                    $_SESSION["logged_user"] = $user;
                    $this->AddRedirect("/");
                }
            }
            else
                $this->AddError("You entered wrong data!");

            echo $this->GetJsonServerAnswer();
            return;
        }

        $this->ProcessApp($app_info);

        $viewbag = [];

        View($viewbag);
    }

    private function GetUserByHash($hash)
    {
        $user = R::getAll("SELECT id, login, is_admin, hash FROM users WHERE hash = ?", [$hash])[0];

        if ($user) {
            return $user;
        } else return false;
    }
}