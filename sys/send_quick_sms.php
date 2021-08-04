<?php

function send_sms($mobile, $text) {
    ob_start();

    $curl_handle = curl_init("http://103.16.59.36/MtSendSMS/SingleSMS.aspx");
    curl_setopt($curl_handle, CURLOPT_URL, 'http://103.16.59.36/MtSendSMS/SingleSMS.aspx');
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "usr=Hiranandani&pass=sandeep&msisdn=91$mobile&msg=$text&sid=INTNET&fl=0&mt=0&intsender=1&typeofmessage=1");
    $curl_return = curl_exec($curl_handle);

    var_dump($curl_return);
    $retval = ob_get_clean();

    curl_close($curl_handle);
    return $retval;
}
?>
