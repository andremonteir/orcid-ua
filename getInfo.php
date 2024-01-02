<?php

if(isset($_REQUEST["access_token"]) && isset($_REQUEST["orcidid"])){
    extract($_REQUEST);
    $data = getOneInfo($access_token, $orcidid);
    $list []= $data["group"];
    header("Content-Type: application/json");
    $list = array_column($list[0], 'work-summary');
    echo json_encode($list);
    exit();
}

function getOneInfo($access_token, $orcidid){
            
    $url = "https://pub.orcid.org/v3.0/" . $orcidid . "/works";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Access-Control-Allow-Origin: *",
        "Accept: application/vnd.orcid+json",
        "Authorization: Bearer $access_token",
        "Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT",
        "Access-Control-Allow-Headers: append,delete,entries,foreach,get,has,keys,set,values,Authorization"
    );

    curl_setopt($curl, CURLOPT_POST, 0);                //0 for a get request
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLINFO_HEADER_OUT, false);

    $response = curl_exec($curl);
    
    curl_close($curl);
    $data = json_decode($response, true);
    return $data;
}

?>