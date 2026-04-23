<?php
header('Content-Type: application/json');

$apiKey = '09415f46f9eef8387220531419c861a0'; 
$urlBps = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/0000/var/455/th/124/key/" . $apiKey . "/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlBps);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT, 'KlinikApp/1.0'); 
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($response) {
    echo $response;
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Gagal terhubung ke server BPS RI. Error: ' . $err
    ]);
}
?>