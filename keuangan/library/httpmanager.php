<?php
require_once ("httprequest.php");

class HttpManager
{
    private int $timeout = 10000;

    private $data;

    function __construct(private $address)
    {
    }

    function setTimeout($ms)
    {
        $this->timeout = $ms;
    }

    function setData($data)
    {
        $this->data = $data;
    }

    function send()
    {
        try
        {
            $httpRequest = new HttpRequest($this->address);
            $httpRequest->setTimeout($this->timeout);
            $httpRequest->setQueryString($this->data);

            return $httpRequest->send(); // return GenericReturn
        }
        catch (Exception $ex)
        {
            return new GenericReturn(-99, $ex->getMessage(), $ex);
        }
    }
}
?>