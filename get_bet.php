<?php

function get_content($url) {
    $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT      => "Mozilla/5.0",
            CURLOPT_COOKIEFILE => "cookie.txt",
    );
    $get = curl_init( $url );
    curl_setopt_array( $get, $options );
    $htmlContent = curl_exec( $get );
    curl_close( $get );
    return $htmlContent;
}

$response = get_content('https://www.bet365.com/#/HO/');
echo $response;


?>
