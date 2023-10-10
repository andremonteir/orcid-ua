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


       // include "Oauth.php";

        $prodUrl = "https://orcid.org/oauth/authorize";
        $redirectUri = "http://localhost:8080/orcid-ua";//"https://wonderful-pebble-03a26b403.3.azurestaticapps.net";
        $clientId = "APP-3CNS49SKAR5OGR9E"; 
        $clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
        $authEndpoint = "https://orcid.org/oauth/authorize";
        $tokenEndpoint = "https://orcid.org/oauth/token";

        $curl = curl_init($authEndpoint);
        curl_setopt($curl, CURLOPT_URL, $authEndpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $postfields2 = array(
            "client_id: $clientId", "response_type: code", 
                "scope:/authenticate", "redirect_uri: $redirectUri"
        );

        $postfields = [
            'client_id' => $clientId,
            'response_type' => 'code',
            'scope'   => "/authenticate",
            "redirect_uri" => $redirectUri
        ];

        $headers = [
            "Access-Control-Allow-Origin:*" => "*",
            "Accept" => "application/json"
        ];
        

        $headers2 = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Access-Control-Allow-Origin:*",
            "Access-Control-Allow-Methods: GET, POST, OPTIONS",
            "Access-Control-Allow-Headers: Accept,authorization,Authorization, Content-Type",
            "Accept: 'application/json"
        );
        curl_setopt($curl,CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields, PHP_QUERY_RFC1738));

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;

        ?>
        <script>

        const prodUrl = "https://orcid.org/oauth/authorize";
        const redirectUri = "https://wonderful-pebble-03a26b403.3.azurestaticapps.net";
        const clientId = "APP-3CNS49SKAR5OGR9E"; 
        const clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
        const authEndpoint = "https://orcid.org/oauth/authorize";
        const tokenEndpoint = "https://orcid.org/oauth/token";

        

        </script>
    </body>
</html>