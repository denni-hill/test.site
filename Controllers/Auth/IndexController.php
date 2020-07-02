<?php
class IndexController extends _IndexController implements iController
{
    function Index(array $app_info)
    {
        if(isset($_SESSION["logged_user"]))
        {
            moveTo("/");
            return;
        }

        if(!empty($_POST))
        {
            $UserModel = new UserModel();
            $User = $UserModel->GetData($_POST);
            if($User)
            {
                $_SESSION["logged_user"] = $User;
                $this->AddRedirect("/");
            }
            else
            {
                $this->AddError("You entered wrong data!");
            }

            echo $this->GetJsonServerAnswer();
            return;
        }

        View();
    }


}