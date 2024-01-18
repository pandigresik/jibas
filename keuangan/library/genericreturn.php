<?php
class GenericReturn
{
    public function __construct(public $Value, public $Text, public $Data)
    {
    }

    public function toJson()
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }

    public static function fromJson($json)
    {
        return json_decode((string) $json, null, 512, JSON_THROW_ON_ERROR);
    }

    public static function createJson($value, $text, $data)
    {
        $result = new GenericReturn($value, $text, $data);
        return $result->toJson();
    }
}
?>