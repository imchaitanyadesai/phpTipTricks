
$contact_number = $phone_number;
$from = TWILIO_FROM_NUMBER;
$to = $contact_number; // twilio trial verified number
$body = "Your " . APPNAME . " App verification code is " . $verify_token;
$data_sms = array(
    'From' => $from,
    'To' => $to,
    'Body' => $body,
);

$response = callTwilioAPI($data_sms);
$response[STATUS] = SUCCESS;
if ($response[STATUS] == SUCCESS) {
    $data['otp']['otp_message'] = $verify_token;
    $data['status'] = SUCCESS;
    $data['message'] = "OTP is send to your number.";
    return $data;
}else {
    return $response;
}


function callTwilioAPI($data){
    $response[STATUS]=FAILED;
    $twilio_account_id = TWILIO_ACCOUNT_SID;
    $token = TWILIO_AUTH_TOKEN;
    $url = "https://api.twilio.com/2010-04-01/Accounts/$twilio_account_id/SMS/Messages";
    $post = http_build_query($data);
    $x = curl_init($url);
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false); //This line is needed otherwise
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_USERPWD, "$twilio_account_id:$token");
    curl_setopt($x, CURLOPT_POSTFIELDS, $post);
    $y = curl_exec($x);
    curl_close($x);
    $xml = simplexml_load_string($y);
    $json = json_encode($xml);
    $array_json = json_decode($json, TRUE);
//    echo '<pre>';
//    print_r($array_json);
//    echo '</pre>';
//    die;
    if ($y != false) {
        if ($array_json['SMSMessage']['Status'] == "queued") {
            $response[STATUS]=SUCCESS;
            return $response;
        }
        else {
            $error['Status'] = $array_json['RestException']['Status'];
            $error['Message'] = $array_json['RestException']['Message'];
            $error['Code'] = $array_json['RestException']['Code'];
            $response[MESSAGE] = "Status " . $error['Status'] . ", Code " . $error['Code'];

            return $response;
        }
    }
}
