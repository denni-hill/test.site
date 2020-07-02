<?php
class EditTaskController extends Base_Controller implements iController
{
    public function Index(array $app_info)
    {
        if(!(isset($_SESSION['logged_user']) && $_SESSION['logged_user']['is_admin'] == 1))
        {
            $this->AddRedirect("/Login/Index");
            echo $this->GetJsonServerAnswer();
            return;
        }

        if(isset($_POST["id"]) && isset($_POST["task"]) &&  isset($_POST["is_completed"]))
        {
            if($this->ValidateEditData($_POST["id"], $_POST["task"], $_POST["is_completed"]))
            {
                $TODOModel = new TODOModel();
                $TODOModel->EditTask($_POST["id"], $_POST["task"], $_POST["is_completed"]);
            }
            echo $this->GetJsonServerAnswer();
        }
        else
        {
            $this->AddError("Input data corrupted!");
            echo $this->GetJsonServerAnswer();
        }
    }

    private function ValidateEditData(int $id, string $task_content, bool $is_completed)
    {
        $TODOModel = new TODOModel();
        if(!$TODOModel->Exists($id)) $this->AddError("No suck task in database!");
        if(strlen($task_content) < 1) $this->AddError("Please, enter task!");
        if($is_completed != 0 && $is_completed != 1 ) $this->AddError("Is completed value is invalid!");

        if(count($this->GetServerAnswer()["Errors"]) > 0) return false;
        else return true;
    }
}