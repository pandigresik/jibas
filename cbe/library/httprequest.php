<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php

require_once ("genericreturn.php");
require_once ("debugger.php");

class HttpRequest
{
    private $host;
    private $port;
    private $uri;
    private $method;
    private $protocol = "http";
    private $timeout = 10000; // version 18.0 on 2022-04-12

    private $queryString;
    private $queryData; // key value array
    private $postData; // key value array
    private $cookieData; // key value array
    private $headerData; // key value array

    function __construct($host, $port = 80, $uri = "/", $method = "POST")
    {
        $this->host = $host;
        $this->port = $port;
        $this->uri = $uri;
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

    function buildHost()
    {
        if ($this->protocol == "http")
        {
            if ($this->port == 80)
                return $this->host;

            return $this->host . ":" . $this->port;
        }
        else if ($this->protocol == "https")
        {
            if ($this->port == 443)
                return $this->host;

            return $this->host . ":" . $this->port;
        }
        else
        {
            return $this->host . ":" . $this->port;
        }
    }

    function send()
    {
        $host = $this->buildHost();
        $url = $this->protocol . "://" . $host;

        $post = $this->queryString;
        foreach ($this->postData as $k => $v)
        {
            if ($post != "") $post .= "&";
            $post .= urlencode($k) . "=" . urlencode($v);
        }

        $ch = curl_init();

        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
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