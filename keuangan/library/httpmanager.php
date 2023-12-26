<?php
require_once ("httprequest.php");

class HttpManager
{
    private $timeout = 10000;

    private $data;

    private $address;

    function __construct($address)
    {
        $this->address = $address;
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