<?php
class GetTaskController extends Base_Controller implements iApi
{
    public function ApiIndex(array $appInfo = [])
    {
        if(isset($_POST["task"])) {
            $TODOModel = new TODOModel();
            $single_task = $TODOModel->GetSingleTask($_POST["task"]);
            if (!is_null($single_task))
                $this->AjaxResponder->AddArgument($single_task);
            else $this->AjaxResponder->AddError("No such task in database!");
            echo $this->AjaxResponder->GetJsonServerAnswer();
            return;
        }
    }
}