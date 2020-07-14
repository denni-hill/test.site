<?php
class IndexController extends _IndexController implements iController, iApi
{
    function Index(array $app_info)
    {
        if(isset($_SESSION["logged_user"]))
        {
            moveTo("/");
            return;
        }

        View();
    }


    public function ApiIndex(array $appInfo = array())
    {
        if(!empty($_POST))
        {
            $UserModel = new UserModel();
            $User = $UserModel->GetData($_POST);
            if($User)
            {
                $_SESSION["logged_user"] = $User;
                $this->AjaxResponder->AddRedirect("/");
            }
            else
            {
                $this->AjaxResponder->AddError("You entered wrong data!");
            }

            echo $this->AjaxResponder->GetJsonServerAnswer();
        }
    }
}