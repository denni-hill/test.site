<?php
class GetTaskController extends Base_Controller implements iController
{
    public function Index(array $app_info)
    {
        if(isset($_POST["task"])) {
            $TODOModel = new TODOModel();
            $single_task = $TODOModel->GetSingleTask($_POST["task"]);
            if (!is_null($single_task))
                $this->AddArgument($single_task);
            else $this->AddError("No such task in database!");
            echo $this->GetJsonServerAnswer();
            return;
        }
    }
}