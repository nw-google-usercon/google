<?php
header('Content-Type: application/json');

// Gelen JSON verisini almak için php://input kullanın
$rawData = file_get_contents("php://input");
$postFields = json_decode($rawData, true);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://cdnevent.ldyunos.com/cloud_phone_error_trace');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
$headers[] = 'application/x-www-form-urlencoded';
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
        'data' => $rrPonse,
        'postFields' => $postFields
    ]);
}
curl_close($ch);
?>

