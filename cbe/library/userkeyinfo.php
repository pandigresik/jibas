<?php
class UserKeyInfo
{
    public $UserId;
    public $SessionId;

    public function toJson()
    {
        return json_encode($this);
    }
}
?>