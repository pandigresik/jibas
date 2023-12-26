<?php
require_once ("genericreturn.php");

class HttpRequest
{
    private $address;
    private $protocol = "http";
    private $timeout = 10000; // version 18.0 on 2022-04-12

    private $queryString;
    private $queryData; // key value array
    private $postData; // key value array
    private $cookieData; // key value array
    private $headerData; // key value array

    function __construct($address, $method = "POST")
    {
        $this->address = $address;
        $this->method = strtoupper($method);
    }

    function setTimeout($ms)
    {
        $this->timeout = $ms;
    }

    function setProtocol($protocol)
    {
        $this->protocol = strtolower($protocol);
    }

    function setQueryString($queryString)
    {
        $this->queryString = $queryString;
    }

    function setQueryData($queryArray)
    {
        $this->queryData = $queryArray;
    }

    function setPostData($postArray)
    {
        $this->postData = $postArray;
    }

    function setCookie($cookieArray)
    {
        $this->cookieData = $cookieArray;
    }

    function setHeader($headerArray)
    {
        $this->headerData = $headerArray;
    }

    function send()
    {
        $post = $this->queryString;

        $ch = curl_init();

        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $this->address,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT_MS => $this->timeout,
            CURLOPT_POSTFIELDS => $post
        );

        curl_setopt_array($ch, $defaults);

        $response = curl_exec($ch);

        if (curl_errno($ch))
        {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);

            return new GenericReturn(-99, "Error $errno: $errstr", "");
        }

        curl_close($ch);

        return new GenericReturn(1, "200", $response);
    }
}
?>