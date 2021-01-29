<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}
$data = [];

$query_string = $qs = $_SERVER['QUERY_STRING'];
parse_str($query_string, $query_array);
$qa = $query_array;

$HTTP_REFERER = array_key_exists("HTTP_REFERER",$_SERVER) ? $_SERVER["HTTP_REFERER"] : null;
$request_method = $_SERVER['REQUEST_METHOD'];
$redirect_url = explode('?',$_SERVER['REQUEST_URI'])[0];

$_GET = $get = array_filter(explode('/',trim($redirect_url,'/')));
$got =  count($_GET);
$query = array_slice($_GET,2);
$params = count($query);

$host = $_SERVER['HTTP_HOST'];
$arrhost = explode('.',$host);
$arrhostcount = count($arrhost);
$domain = $arrhost[count($arrhost)-2];
$tld = $arrhost[count($arrhost)-1];

$request_uri = $_SERVER['REQUEST_URI'];

$api = 'api.'.$domain.'.'.$tld;
$beta = 'beta.'.$domain.'.'.$tld;

$log = false;
$server = [
  'HTTP_HOST' => $host,
  'HOST_ARRAY' => $arrhost,
  'HOST_ARRAY_LENGTH' => $arrhostcount,
  'HOST_DOMAIN' => $domain,
  'HOST_TLD' => $tld,
  'REDIRECT_URL' => $redirect_url,
  'REQUEST_URI' => $request_uri
];
if($log == true) {
  $data["server"] = $server;
}


if(
  ($host === $api && array_key_exists('HTTP_ORIGIN',$_SERVER))
) {
    
  $_POST = json_decode(file_get_contents('php://input'),true);

  if(count($_GET) > 1) { include(__DIR__.'/../'.$_GET[0].'/index.php'); }

} else {

  http_response_code(404);

}
unset($data);
?>
