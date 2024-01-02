<?php

if(isset($_REQUEST["access_token"])){
    extract($_REQUEST);
    $data = getPersonDetail($access_token, $q);
    header("Content-Type: application/json");
    echo json_encode($data);
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

function getPersonDetail($access_token, $orcidid)
    {
        $rowsDetail = array();
        $data = getOneInfo($access_token, $orcidid);

        $works = count($data["group"]);

        if ($works>0)
        {
            foreach ($data["group"] as $value) {
                
                $url = isset($value["work-summary"][0]["url"]) ?  $value["work-summary"][0]["url"]["value"] : "";
                $year = isset($value["work-summary"][0]["publication-date"]["year"]) ? $value["work-summary"][0]["publication-date"]["year"]["value"] : "";
                $title = $value["work-summary"][0]["title"]["title"]["value"];
                $journal = isset($value["work-summary"][0]["journal-title"]) ? $value["work-summary"][0]["journal-title"]["value"]: "";
                $rowDetail = array("year" => $year, "title" => $title, "url" => $url, "journal" => $journal );

                array_push($rowsDetail, $rowDetail);
            }
        }
        return $rowsDetail;
    }


?>

    