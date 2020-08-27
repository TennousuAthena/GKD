<?php
$config = include_once("config.php");
$pathinfo = explode('/',$_SERVER["REQUEST_URI"]);
define("CONTROLLER", $pathinfo[1]);
$furl = $pathinfo;
unset($furl[1]);
$furl = implode("/", $furl);
define("FURL", $furl);
array_walk($config, function (&$item) {
    global $r404;
    if(($item['name']) == CONTROLLER){
        echo _httpGet($item['domain'], "http://localhost".FURL);
        $r404 = 1;
    }
});

if(!$r404){
    include_once("404.html");
    http_response_code(404);
}

function _httpGet($host="", $url="http://localhost"){
        
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);
        !empty ($host) && curl_setopt($curl, CURLOPT_HTTPHEADER, array("Host: $host"));

        $res = curl_exec($curl);
        $info = curl_getinfo($curl);
        http_response_code($info['http_code']);
        header("Content-Type: ".$info["content_type"]);
        curl_close($curl);
        return $res;
    }
