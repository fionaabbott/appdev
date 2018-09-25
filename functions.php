<?php
function makeCurlCall($url){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Basic ZmlvbmEuYWJib3R0QHN1cHBvcnR1dy5vcmc6WW91IGFyZSBteSBzdW5zaGluZSEgMjAxOA==",
        "Cache-Control: no-cache"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);

    if($err){
        echo "cURL Error #: ".$err;
    }
    
    curl_close($curl);
    return $response;
}

//builds a list of links based on results from a call to the Confluence API

function buildLinks($response, &$links){
    foreach($response->results as $key=>$value){
        $url = "https://abecrm.atlassian.net/wiki".$value->_links->webui;
        $links .= '<a href="'.$url.'" target="_blank">'.$value->title.'</a><br />';
    }
}

?>