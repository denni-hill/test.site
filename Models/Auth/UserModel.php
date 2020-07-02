<?php
class UserModel implements iModel
{
    public function GetData($params = [])
    {
        if(isset($params["hash"]))
        {
            return $this->GetUserByHash($params["hash"]);
        }
        else return false;
    }

    private function GetUserByHash($hash)
    {
        $user = R::getAll("SELECT id, login, is_admin, hash FROM users WHERE hash = ?", [$hash])[0];

        if ($user) {
            return $user;
        } else return false;
    }
}