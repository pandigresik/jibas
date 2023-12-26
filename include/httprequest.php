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
function http_request(
    $verb = 'GET',             /* HTTP Request Method (GET and POST supported) */
    $ip,                       /* Target IP/Hostname */
    $port = 80,                /* Target TCP port */
    $uri = '/',                /* Target URI */
    $getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */
    $postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */
    $cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */
    $custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */
    $timeout = 7000,           /* Socket timeout in milliseconds */
    $req_hdr = false,          /* Include HTTP request headers */
    $res_hdr = false           /* Include HTTP response headers */
    )
{
    $ret = '';
    $verb = strtoupper($verb);
    $cookie_str = '';
    $getdata_str = count($getdata) ? '?' : '';
    $postdata_str = '';

    foreach ($getdata as $k => $v)
        $getdata_str .= urlencode($k) .'='. urlencode($v);

    foreach ($postdata as $k => $v)
        $postdata_str .= urlencode($k) .'='. urlencode($v) .'&';

    foreach ($cookie as $k => $v)
        $cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';

    $crlf = "\r\n";
    $req = $verb .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf;
    $req .= 'Host: '. $ip . $crlf;
    $req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf;
    $req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf;
    $req .= 'Accept-Language: en-us,en;q=0.5' . $crlf;
    $req .= 'Accept-Encoding: deflate' . $crlf;
    $req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf;
   
    foreach ($custom_headers as $k => $v)
        $req .= $k .': '. $v . $crlf;
       
    if (!empty($cookie_str))
        $req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf;
       
    if ($verb == 'POST' && !empty($postdata_str))
    {
        $postdata_str = substr($postdata_str, 0, -1);
        $req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf;
        $req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf;
        $req .= $postdata_str;
    }
    else $req .= $crlf;
   
    if ($req_hdr)
        $ret .= $req;
   
    if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false)
        return "Error $errno: $errstr\n";
   
    stream_set_timeout($fp, 0, $timeout * 1000);
   
    fputs($fp, $req);
    while ($line = fgets($fp)) $ret .= $line;
    fclose($fp);
   
    if (!$res_hdr)
        $ret = substr($ret, strpos($ret, "\r\n\r\n") + 4);
   
    return $ret;
}
?>