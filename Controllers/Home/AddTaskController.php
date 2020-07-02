<?php
class AddTaskController extends Base_Controller implements iController
{
    public function Index(array $app_info)
    {
        if(isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["task"])) {
            if ($this->ValidateInputData($_POST["name"], $_POST["email"], $_POST["task"])) {
                $TODOModel = new TODOModel();
                $TODOModel->CreateNewTask($_POST["name"], $_POST["email"], $_POST["task"]);
            }
            echo $this->GetJsonServerAnswer();
        }
        else
        {
            $this->AddError("Input data corrupted!");
            echo $this->GetJsonServerAnswer();
        }
    }

    private function ValidateInputData(string $name, string $email, string $task)
    {
        if(strlen($name) < 1) $this->AddError("Please, enter your name!");
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->AddError("Given email hasn't valid format!");
        if(strlen($task) < 1) $this->AddError("Please, enter task!");

        if(count($this->GetServerAnswer()["Errors"]) > 0) return false;
        else return true;
    }
}