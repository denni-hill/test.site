<?php
class AddTaskController extends Base_Controller implements iApi
{
    private function ValidateInputData(string $name, string $email, string $task)
    {
        if(strlen($name) < 1) $this->AjaxResponder->AddError("Please, enter your name!");
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->AjaxResponder->AddError("Given email hasn't valid format!");
        if(strlen($task) < 1) $this->AjaxResponder->AddError("Please, enter task!");

        if(count($this->AjaxResponder->GetServerAnswer()["Errors"]) > 0) return false;
        else return true;
    }

    public function ApiIndex(array $appInfo = array())
    {
        if(isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["task"])) {
            if ($this->ValidateInputData($_POST["name"], $_POST["email"], $_POST["task"])) {
                $TODOModel = new TODOModel();
                $TODOModel->CreateNewTask($_POST["name"], $_POST["email"], $_POST["task"]);
            }
            echo $this->AjaxResponder->GetJsonServerAnswer();
        }
        else
        {
            $this->AjaxResponder->AddError("Input data corrupted!");
            echo $this->AjaxResponder->GetJsonServerAnswer();
        }
    }
}