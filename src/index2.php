<?php
namespace Orcid;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <h1>ISCA-UA ORCID Summary</h1>
        <br>
        <pre id="result">...</pre>
        <input type="text" id="token" placeholder="tokenid">
        <input type="text" id="search" placeholder="search items" value="demeranville">
        <input type="submit" value="submit">
        <?php


        $redirectUri = "http://localhost:8080/orcid-ua";//"https://wonderful-pebble-03a26b403.3.azurestaticapps.net";
        $clientId = "APP-3CNS49SKAR5OGR9E"; 
        $clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
        $authEndpoint = "https://orcid.org/oauth/authorize";
        $tokenEndpoint = "https://orcid.org/oauth/token";
        $url = "https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=http%3A%2F%2Flocalhost%3A&client_id=" . $clientId . "&scope=openid";
        // https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=http%3A%2F%2Flocalhost%3A&client_id=APP-3CNS49SKAR5OGR9E&scope=openid"

        // Read JSON file
        $json = file_get_contents($url);
    
        //Decode JSON
        $json_data = json_decode($json,true);
        print_r($json_data);
        echo $json_data["access_token"];
        //Traverse array and get the data for students aged less than 20
       /* foreach ($json_data as $key1 => $value1) {
            if($json_data[$key1]["allTests"][7]['TestOnBlazemeterWasBroken'] == true){
                print_r($json_data[$key1]);
            }
        }
*/
        ?>
        <script>

      

        </script>
    </body>
</html>