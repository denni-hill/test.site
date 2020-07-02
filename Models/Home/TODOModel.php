<?php
class TODOModel implements iModel
{
    public function GetData($params = [])
    {
        $rows_per_page = 3;

        $limit_sql_query_part = "";
        $page = $this->ValidatePageNumber($params["page"], $rows_per_page);
        $limit_sql_query_part = " LIMIT " . ($page-1)*$rows_per_page . "," . $rows_per_page;


        $order_sql_query_part = "";
        $sort_validated = $this->ValidateSortBy($params["sortby"]);
        if($sort_validated)
        {
            $sort_by = $params["sortby"];
            $sort_direction = strtolower($params["sortdir"]) == "desc" ? "DESC" : "ASC";
            $order_sql_query_part = " ORDER BY " . $sort_by . " " . $sort_direction;
        }

        $pagination = $this->GetPagination($page, $rows_per_page, "/sortby=" . $params["sortby"] . "/sortdir=" . $params["sortdir"]);
        $items = R::getAll("SELECT id, username, email, task_content, is_completed, is_edited FROM tasks" . $order_sql_query_part . $limit_sql_query_part);
        return ["items" => $items, "pagination" => $pagination, "current_page_link" => $_SERVER["REQUEST_URI"]];
    }

    public function CreateNewTask($name, $email, $task_content)
    {
        $task = R::dispense("tasks");
        $task["username"] = $name;
        $task["email"] = $email;
        $task["task_content"] = $task_content;
        R::store($task);
    }

    public function GetSingleTask($id)
    {
        $task = R::getAll("SELECT * FROM tasks WHERE id = ?", [$id]);
        if($task)
            return $task[0];
        else return null;
    }

    public function EditTask($id, $task_content, $is_completed)
    {
        if($this->Exists($id))
        {
            $task = R::load("tasks", $id);
            if($task["task_content"] != $task_content)
                $task["is_edited"] = 1;
            $task["task_content"] = $task_content;
            $task["is_completed"] = $is_completed;
            R::store($task);
        }
    }

    public function Exists($id)
    {
        return !is_null(R::getAll("SELECT * FROM tasks WHERE id = ?", [$id]));
    }

    private function GetPagination($page, $rows_per_page, $sorting)
    {
        $pagination = [];
        $total_items_count = R::getCell("SELECT COUNT(*) FROM tasks");
        $pages_count = ceil($total_items_count / $rows_per_page);

        if($page > $pages_count) $params["page"] = $pages_count;

        if($page > 1)
            $pagination[] = ["link" => "/Home/Index/page=" . ($page-1), "number" => "Previous"];

        for($i = 1; $i <= $pages_count; $i++)
        {
            $pagination[] = ["link" => "/Home/Index/page=" . $i, "number" => $i];
        }

        if($page < $pages_count)
            $pagination[] = ["link" => "/Home/Index/page=" . ($page+1), "number" => "Next"];

        return $pagination;
    }

    private function ValidatePageNumber($page, $rows_per_page)
    {
        if(!is_numeric((int)$page)) return 1;
        else
        {
            $total_items_count = R::getCell("SELECT COUNT(*) FROM tasks");
            $pages_count = ceil($total_items_count / $rows_per_page);
            if($page > $pages_count) $params["page"] = $pages_count;
            if($page < 1) $page = 1;
        }
        return $page;
    }

    private function ValidateSortBy($sortby)
    {
        return in_array($sortby, array_keys(R::inspect("tasks")));
    }
}