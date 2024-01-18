<?php
class UserKeyInfo
{
    public $UserId;
    public $SessionId;

    public function toJson()
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }
}
?>