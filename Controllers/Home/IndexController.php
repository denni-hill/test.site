<?php
class IndexController extends _IndexController implements iController
{
    function Index(array $app_info)
    {
        $this->ProcessApp($app_info);

        if(!empty($_POST))
        {
            if(!(isset($_SESSION['logged_user']) && $_SESSION['logged_user']['is_admin'] == 1))
            {
                $this->AddRedirect("/Login/Index");
                echo $this->GetJsonServerAnswer();
                return;
            }
            if(strtolower($app_info["uri_parts"][1]) == "addtask") {
                if ($this->ValidateInputData($_POST["name"], $_POST["email"], $_POST["task"])) {
                    $TODOModel = new TODOModel();
                    $TODOModel->CreateNewTask($_POST["name"], $_POST["email"], $_POST["task"]);
                }
                echo $this->GetJsonServerAnswer();
                return;
            }
            else if(strtolower($app_info["uri_parts"][1]) == "edittask")
            {
                if($this->ValidateEditData($_POST["id"], $_POST["task"], $_POST["is_completed"]))
                {
                    $TODOModel = new TODOModel();
                    $TODOModel->EditTask($_POST["id"], $_POST["task"], $_POST["is_completed"]);
                }
                echo $this->GetJsonServerAnswer();
                return;
            }
        }

        if(strtolower($app_info["uri_parts"][1]) == "gettask")
        {
            $TODOModel = new TODOModel();
            $single_task = $TODOModel->GetSingleTask($app_info["args"]["task"]);
            if(!is_null($single_task))
                $this->AddArgument($single_task);
            else $this->AddError("No such task in database!");
            echo $this->GetJsonServerAnswer();
            return;
        }

        $TODOModel = new TODOModel();

        $viewbag = [
            "TODO_data" => $TODOModel->GetData($app_info["args"])
        ];

        View($viewbag);
    }

    private function ValidateInputData(string $name, string $email, string $task)
    {
        if(strlen($name) < 1) $this->AddError("Please, enter your name!");
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->AddError("Given email hasn't valid format!");
        if(strlen($task) < 1) $this->AddError("Please, enter task!");

        if(count($this->GetServerAnswer()["Errors"]) > 0) return false;
        else return true;
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