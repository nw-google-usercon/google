<?php
header('Content-Type: application/json');
function generateRandomHexCode()
{
    $length = 32;
    $chars = '0123456789abcdef';
    $randomCode = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($chars) - 1);
        $randomCode .= $chars[$randomIndex];
    }

    return $randomCode;
}


$proxyList = [
    '38.154.227.167:5868:msvbdldw:msesgxqeat2m',
    '64.64.118.149:6732:msvbdldw:msesgxqeat2m',
    '167.160.180.203:6754:msvbdldw:msesgxqeat2m',
    '166.88.58.10:5735:msvbdldw:msesgxqeat2m',
    '173.0.9.70:5653:msvbdldw:msesgxqeat2m',
    '204.44.69.89:6342:msvbdldw:msesgxqeat2m',
    '173.0.9.209:5792:msvbdldw:msesgxqeat2m',
];

function getRandomProxy($proxyList)
{
    $randomProxy = $proxyList[array_rand($proxyList)];
    list($ip, $port, $username, $password) = explode(':', $randomProxy);
    return [
        'proxy' => "http://$ip:$port",
        'username' => $username,
        'password' => $password
    ];
}


$proxyData = getRandomProxy($proxyList);
$proxy = $proxyData['proxy'];
$username = $proxyData['username'];
$password = $proxyData['password'];


$ch = curl_init();


curl_setopt($ch, CURLOPT_URL, "https://api.easyfun.gg/tourists/" . generateRandomHexCode());
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "accept: */*",
    "accept-language: en-US,en;q=0.9",
    "content-length: 0",
    "origin: https://www.easyfun.gg",
    "referer: https://www.easyfun.gg/",
    'sec-ch-ua: "Opera GX";v="109", "Not:A-Brand";v="8", "Chromium";v="123"',
    "sec-ch-ua-mobile: ?0",
    'sec-ch-ua-platform: "Windows"',
    "sec-fetch-dest: empty",
    "sec-fetch-mode: cors",
    "sec-fetch-site: same-site",
    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0"
]);


$response = curl_exec($ch);


if (curl_errno($ch)) {
    echo json_encode([
        'status' => false,
        'message' => 'Curl error: ' . curl_error($ch)
    ]);
} else {
    $encoded = json_encode($response);
    $decoded = json_decode($encoded, true);
    $rPonse = json_decode($response, true);

    $postData = [
        'uid' => $rPonse['data']['uid'],
        'token' => $rPonse['data']['token'],
        'gameId' => '1',
        'fingerprint' => $rPonse['data']['fingerprint'],
        'email' => '',
        'platform' => ''
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.easyfun.gg/auth/socket');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Origin: https://www.easyfun.gg';
    $headers[] = 'Referer: https://www.easyfun.gg/';
    $headers[] = '^\"Sec-Ch-Ua: ';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = '^\"Sec-Ch-Ua-Platform: ';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode([
            'status' => false,
            'message' => 'Curl error: ' . curl_error($ch)
        ]);
    } else {
        $encoded = json_encode($result);
        $decoded = json_decode($encoded, true);
        $rrPonse = json_decode($result, true);
        
        echo json_encode([
            'status' => true,
            'data' => [
                'socket' => $rrPonse['data'],
                'data' => $rPonse['data']
            ]
        ]);
    }
    curl_close($ch);
}

curl_close($ch);
