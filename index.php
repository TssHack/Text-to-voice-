<?php
header('Content-Type: application/json');

$text = $_GET['text'];
$type = $_GET['type'];



function generateRandomText($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

if (isset($_GET['text']) && isset($_GET['type'])) {

    $txt = str_replace('+', '', $text);

    $data = json_encode([
        'ttsService' => 'azure',
        'audioKey' => generateRandomText(10),
        'storageService' => 's3',
        'text' => "<speak><p>$txt</p></speak>",
        'wordCount' => 8,
        'voice' => [
            'value' => "fa-IR-$type",
            'lang' => 'fa-IR'
        ],
        'audioOutput' => [
            'fileFormat' => 'mp3',
            'sampleRate' => 24000
        ]
    ]);
    $headers = array(
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://bff.listnr.tech/backend/ttsNewDemo');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $jsonData = json_decode($response, true);

    if ($jsonData['success'] != false) {
        echo json_encode([
            'error' => false,
            'Dev' => 'Devehsan',
            'result' => $jsonData['url']
        ], 488);
    } elseif ($jsonData['success'] == false) {

        echo json_encode([
            'error' => true,
            'Dev' => 'Devehsan',
            'result' => 'Error in the construction of voice !'
        ], 488);

    }
} else {
    echo json_encode([
        'error' => true,
        'Dev' => 'Devehsan',
        'result' => 'Error No Paramter : type or text'
    ], 488);

}
