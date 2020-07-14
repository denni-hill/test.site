<?php
class EditTaskController extends Base_Controller implements iApi
{
    public function ApiIndex(array $appInfo = [])
    {
        if(!(isset($_SESSION['logged_user']) && $_SESSION['logged_user']['is_admin'] == 1))
        {
            $this->AjaxResponder->AddRedirect("/Auth/Index");
            echo $this->AjaxResponder->GetJsonServerAnswer();
            return;
        }

        if(isset($_POST["id"]) && isset($_POST["task"]) &&  isset($_POST["is_completed"]))
        {
            if($this->ValidateEditData($_POST["id"], $_POST["task"], $_POST["is_completed"]))
            {
                $TODOModel = new TODOModel();
                $TODOModel->EditTask($_POST["id"], $_POST["task"], $_POST["is_completed"]);
            }
            echo $this->AjaxResponder->GetJsonServerAnswer();
        }
        else
        {
            $this->AjaxResponder->AddError("Input data corrupted!");
            echo $this->AjaxResponder->GetJsonServerAnswer();
        }
    }

    private function ValidateEditData(int $id, string $task_content, bool $is_completed)
    {
        $TODOModel = new TODOModel();
        if(!$TODOModel->Exists($id)) $this->AjaxResponder->AddError("No suck task in database!");
        if(strlen($task_content) < 1) $this->AjaxResponder->AddError("Please, enter task!");
        if($is_completed != 0 && $is_completed != 1 ) $this->AjaxResponder->AddError("Is completed value is invalid!");

        if(count($this->AjaxResponder->GetServerAnswer()["Errors"]) > 0) return false;
        else return true;
    }
}