<?php


$curl = curl_init();
$requestType = 'GET';
$url = 'https://yourpetpa.com.au/collections/dog';
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_CUSTOMREQUEST => $requestType,
    CURLOPT_TIMEOUT => 30,
]);
$response = curl_exec($curl);
curl_close($curl);

echo $response;
