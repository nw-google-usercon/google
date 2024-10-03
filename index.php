<?php
$proxies = file('proxies.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$randomProxy = $proxies[array_rand($proxies)];
list($proxyHost, $proxyPort, $proxyUser, $proxyPass) = explode(':', $randomProxy);

$auth = base64_encode("$proxyUser:$proxyPass");


$context = stream_context_create(array(
    'http' => array(
        'proxy' => "tcp://$proxyHost:$proxyPort",
        'request_fulluri' => true,
        'header' => "Proxy-Authorization: Basic $auth\r\n" .
            "User-Agent: Mozilla/5.0 (Windows NT 6.2; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0"
    ),
    'socket' => array(
        'bindto' => "0:0", // Local address:port, 0:0 for any
    )
));

$url = 'https://now.gg/apps/roblox-corporation/5349/roblox.html';
$output = @file_get_contents($url, false, $context);

if ($output === FALSE) {
    echo "Error";
} else {
    echo $output;
}
