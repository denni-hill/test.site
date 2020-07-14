<?php

/**
 * Class DataBaseProvider
 * can be used to store static functions to work with db. Not recommended to write here functions, which could be written inside the models.
 */
class DataBaseProvider
{
    public $AjaxResponder;

    public function __construct(AjaxResponder $Responder = NULL)
    {
        $this->AjaxResponder = $Responder == NULL ? new AjaxResponder() : $Responder;
    }

    public static function setup()
    {
        if(!R::testConnection()) {
            $db = [
                'host' => 'localhost',      //DEFINE DATABASE HOST
                'port' => '',               //DEFINE DATABASE PORT
                'name' => 'dennihill',      //DEFINE DATABASE
                'user' => 'dennihill',      //DEFINE DATABASE USER
                'password' => 'Densim51'    //DEFINE DATABASE USER PASSWORD
            ];

            R::setup('mysql:host=' . $db['host'] . ';dbname=' . $db['name'] . (strlen($db['port'] == 0) ? "" : ";port=" . $db['port']) , $db['user'], $db['password']);
        }
    }

    public function CreateRow(string $table, array $data)
    {
        $row = R::dispense($table);
        $row_keys = array_keys(R::inspect($table));
        foreach ($row_keys as $key) {
            if($key != "id")
            {
                $row[$key] = $data[$key] == "" ? NULL : $data[$key];
            }
        }

        try
        {
            R::store($row);
        }
        catch (Exception $e)
        {
            $this->AjaxResponder->AddError($e->getMessage());
            $this->AjaxResponder->AddConsoleLog($e->getMessage());
            return false;
        }

        return true;
    }

    public function UpdateRow(string $table, int $id, array $data)
    {
        if(!$this->RowExists($table, $id))
        {
            return false;
        }

        $row_keys = array_keys(R::inspect($table));
        $row_copy = R::dispense($table);
        $row = R::load($table, $id);
        foreach ($row_keys as $key) {
            if ($key != "id")
            {
                $row_copy[$key] = $row[$key];
                if(isset($data[$key]))
                {
                    $row[$key] = $data[$key] == "" ? NULL : $data[$key];
                }
                else
                {
                    $row[$key] = $row_copy[$key];
                }
            }
        }

        $row["updating_timestamp"] = date('Y-m-d H:i:s');
        $row["updated_by_user_id"] = $_SESSION["logged_user"]["id"];

        $row_copy["new_record_id"] = $row["id"];
        $old_record_id = $row["old_record_id"];
        if(!$this->is_null_val($old_record_id))
        {
            $row_copy["old_record_id"] = $old_record_id;
        }
        $row_copy_id = R::store($row_copy);
        $row["old_record_id"] = $row_copy_id;
        try
        {
            R::store($row);
        }
        catch (Exception $e)
        {
            $this->AjaxResponder->AddError($e->getMessage());
            $this->AjaxResponder->AddConsoleLog($e->getMessage());
            return false;
        }
        if(!$this->is_null_val($old_record_id))
        {
            $old_row = R::load($table, $old_record_id);
            $old_row["new_record_id"] = $row_copy_id;
            R::store($old_row);
        }
        return true;
    }

    public function DeleteRow(string $table, int $id)
    {
        if(!$this->RowExists($table, $id)) return false;
        return $this->UpdateRow($table, $id, ["is_deleted" => 1]);
    }

    public function RowExists(string $table, int $id)
    {
        $row = R::findOne($table, "id = ?", [$id]);
        return !is_null($row);
    }

    public function is_null_val($cell_value)
    {
        return $cell_value == NULL;
    }

    public function GetSearchQueryPart(string $target_table, string $to_search)
    {
        if(strlen($to_search) == 0) return "";
        $columns = [];
        if(!is_null($to_search))
        {
            $structure = R::inspect($target_table);
            foreach ($structure as $key => $value)
            {
                $columns[] = "`".$key . "` LIKE '%".$to_search."%'";
            }
        }

        return join(" OR ", $columns);
    }
}