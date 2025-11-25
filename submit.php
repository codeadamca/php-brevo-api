<?php

$env = file(__DIR__.'/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach($env as $value)
{
  $value = explode('=', $value);  
  define($value[0], $value[1]);
}

$message = '
You have received a contact for submission:

Name: '.$_POST['name'].'
Email: '.$_POST['email'].'

';

/*
mail(
    'thomasadam83@hotmail.com',
    'Contact form Submission',
    $message
);
*/;

$curl = curl_init();

$data = json_encode([
    'sender' => [
        'email' => 'thomasadam83@hotmail.com',
        'name' => 'Adam Thomas',
        
    ],
    'to' => [
        [
            'email' => 'thomasadam83@hotmail.com',
            'name' => 'Adam Thomas'
        ]
    ],
    'subject' => 'Contact form Submission',
    'htmlContent' => $message
]);

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.brevo.com/v3/smtp/email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "api-key: " . BREVO_API_KEY ,
        "content-type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}

header('Location: thankyou.html');