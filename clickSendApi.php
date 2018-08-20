$contact_number = $phone_number;
$from = "";
$to = $contact_number;//'+61411111111'; // click send api test number
$body = "Your " . APPNAME . " App verification code is " . $verify_token;
$data_sms = array(
    'messages'=>array(
        array(
            'source'=>'php',
            'from'=>$from,
            'body'=>$body,
            'to'=>$to,
            'schedule'=>'',
            'custom_string'=>''
        )
    )
);

$response = callClickSendAPI($data_sms);
$response[STATUS] = SUCCESS;
if ($response[STATUS] == SUCCESS) {
    $data['otp']['otp_message'] = $verify_token;
    $data['status'] = SUCCESS;
    $data['message'] = "OTP is send to your number.";
    return $data;
}else {
    return $response;
}




function callClickSendAPI($data){
    $response[STATUS]=FAILED;
    $username = API_USERNAME;
    $apiKey = API_KEY;
    $url = "https://rest.clicksend.com/v3/sms/send";
    $post = json_encode($data);

    $x = curl_init($url);
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_HEADER, FALSE);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false); //This line is needed otherwise
    curl_setopt($x, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic ' . base64_encode("$username:$apiKey")));
    curl_setopt($x, CURLOPT_POSTFIELDS, $post);
    $y = curl_exec($x);
    
    $array_json = json_decode($y, TRUE);
  
    if ($y != false) {
        if ($array_json['response_code'] == "SUCCESS") {
            $response[STATUS]=SUCCESS;
            return $response;
        }else {
            $response[MESSAGE] = "Status " . $array_json['response_code'] . ", Message " . $array_json['response_msg'];
            return $response;
        }
    }
}
