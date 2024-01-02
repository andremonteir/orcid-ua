<?php

if(isset($_REQUEST["access_token"])){
    extract($_REQUEST);
    $data = getPeopleInfo($access_token, $q);
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

function getPersonInfo($access_token, $orcidid){
            
    $url = "https://pub.orcid.org/v3.0/" . $orcidid . "/employments";

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

function getPeopleInfo($access_token, $search)
    {
        $orcidids= getISCAUAIds($access_token, $search);
        $i = 0;
        $list = array();
        $rowsDetail = array();

        foreach($orcidids as $orcidid){
            $data = getOneInfo($access_token, $orcidid);
            $person= getPersonInfo($access_token, $orcidid);

            $i++;
        
            $works = count($data["group"]);
            $typeJournal = $typeMagazine = $typeConference = $typeDissertation = $typeBook = $typeOthers = 0;

            if(!is_null($person["affiliation-group"]))
                if (count($person["affiliation-group"])>0)
                {
                    $organization = $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["organization"]["name"];

                    // Avoid wrong results from orcid
                    if (!str_contains($organization, "Aveiro")){
                        $i--;
                        continue;
                    }

                    // Filter works
                    /*foreach ($data["group"] as $value) {
                        $type = strtolower($value["work-summary"][0]["type"]);

                        if (str_contains($type, "journal"))
                            $typeJournal++;
                        else if (str_contains($type, "magazine"))
                            $typeMagazine++;
                        else if (str_contains($type, "conference"))
                            $typeConference++;
                        else if (str_contains($type, "dissertation"))
                            $typeDissertation++;
                        else if (str_contains($type, "book"))
                            $typeBook++;
                        else
                            $typeOthers++;
                    }*/

                    $row = array("name" => $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["source"]["source-name"]["value"], 
                                "role" => $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["role-title"],
                                "organization" => $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["organization"]["name"],
                                "orcid" => $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["source"]["source-orcid"]["path"],
                                "work_count" => count($data["group"])
                            );
                     //print("<pre>".print_r($row, true)."</pre>");echo "<hr>";
                
                    array_push($list, $row);
                    
                }
            if($i==4) // just a few record do test
                    break;
        }
        return $list;
    }

    
    function getISCAUAIds($access_token, $query){
        
        $url = "https://pub.orcid.org/v3.0/search/?q=" . str_replace('%20OR%20', '+OR+', str_replace(' ', '%20', str_replace('"', '%22', $query))); 
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/vnd.orcid+json",
        );

        curl_setopt($curl, CURLOPT_POST, 0);                //0 for a get request
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, false);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $dataRes = array();
        $data = json_decode($response, true);

         if(!is_null($data))
            //if(!is_null($data["result"]))
                $dataRes = $data["result"];
        
        $orcidids = array();

        foreach ($dataRes as $element) {
            array_push($orcidids, $element["orcid-identifier"] ["path"]);
        }
        return $orcidids;
    }

?>

    