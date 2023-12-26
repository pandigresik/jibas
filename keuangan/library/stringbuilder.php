<?php
class StringBuilder
{
    private $list;

    public function Append($string)
    {
        $this->list[] = $string;
    }

    public function AppendLine($string)
    {
        $this->list[] = $string . "\r\n";
    }

    public function AppendBr($string)
    {
        $this->list[] = $string . "<br>";
    }

    public function ToString()
    {
        $result = "";
        for($i = 0; $i < count($this->list); $i++)
        {
            $result .= $this->list[$i];
        }

        return $result;
    }
}
?>