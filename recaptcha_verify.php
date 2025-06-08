<?php
// Replace with your actual reCAPTCHA secret key from Google
$secret = "6LfQR1krAAAAAJq7ovH-moj1navoDnkl_bQyDpAg"; 

$response = $_POST['g-recaptcha-response'] ?? '';

if (empty($response)) {
    die("Please complete the reCAPTCHA.");
}

$verifyURL = "https://www.google.com/recaptcha/api/siteverify";
$data = [
    'secret' => $secret,
    'response' => $response
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($data)
    ]
];

$context  = stream_context_create($options);
$verify = file_get_contents($verifyURL, false, $context);
$captchaSuccess = json_decode($verify);

if (!$captchaSuccess->success) {
    die("reCAPTCHA verification failed.");
}
?>
