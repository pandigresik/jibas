<?php
class GenericReturn
{
    public $Value;
    public $Text;
    public $Data;

    public function __construct($value, $text, $data)
    {
        $this->Value = $value;
        $this->Text = $text;
        $this->Data = $data;
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public static function fromJson($json)
    {
        return json_decode($json);
    }

    public static function createJson($value, $text, $data)
    {
        $result = new GenericReturn($value, $text, $data);
        return $result->toJson();
    }
}
?>