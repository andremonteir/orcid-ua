<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORCID-UA</title>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body onload="authorizeRequest()">
<nav style="height:70px" class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow"><img height="52px" src="https://www.ua.pt/imgs/logo_mobile.svg" ><a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#"> ORCID ISCA-UA</a><img height="52px" src="https://api-assets.ua.pt/files/logos/logo_isca.svg" ></nav>
<br><br><br>
    <?php include "credentials.php"; ?>    
    <div class="container">
    <div class="mb-3 row">
        <label for="access_token1" class="form-label">Access token&nbsp;</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="<?php if (isset($_REQUEST["access_token"])) echo $_REQUEST["access_token"]; else echo "" ?>" name="access_token1" id="access_token1">
            <button class="btn btn-primary mt-3" onclick="authorizeRequest();">Get new token</button>
        </div>
    </div>
    <form>
    <div class="mb-3 row">
        <label for="search" class="form-label">Search params&nbsp;</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value='<?php echo $searchString ?>' name="search" id="search">
        </div>
    </div>
    <div class="mb-3 row">
        <input type="hidden" value="c53ec2b9-971d-4139-ae92-38fd4c34462e" name="access_token" id="access_token"><br>
        <input type="hidden" value="f6c18c68-3e29-4ca0-9eb1-6992d5ba1de6" name="refresh_token" id="refresh_token"><br>
        <div class="col-sm-10">
            <input type="hidden" value="0000-0003-0779-9145" name="orcidid" id="orcidid" class="form-control">
            <input type="submit" value="Get info" class="btn btn-primary mt-3" onclick='$("#userInfo").html("processing")'><button disabled class="btn btn-warning mt-3" id="userInfo">0</button>
        </div>

    </div>
    </form>
        <?php

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

        function getISCAUAIds($access_token, $query){
            
            $url = "https://pub.orcid.org/v3.0/search/?q=".$query; //affiliation-org-name:(%22Universidade%20de%20Aveiro%20instituto%22)
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

           // if(!is_null($data))
                if(!is_null($data["result"]))
                    $dataRes = $data["result"];
            
            $orcidids = array();

            foreach ($dataRes as $element) {
                array_push($orcidids, $element["orcid-identifier"] ["path"]);
            }
            return $orcidids;
        }

        

        function authorizeRequest($clientId, $clientSecret){
            
            $url = "https://orcid.org/oauth/token"; 
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Accept: application/vnd.orcid+json",
            );

            $params = array(
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                "grant_type" => "client_credentials",
                "scope" => "/read-public"
            );

            curl_setopt($curl, CURLOPT_POST, 1);                //0 for a get request
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, false);
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response, true);
      print_r($data);
            return $data["access_token"];
        }

        $access_token =  isset($_REQUEST["access_token"]) ? $_REQUEST["access_token"] : "";
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "affiliation-org-name:(%22Universidade%20de%20Aveiro%20instituto%22)";
        
        // If submitted
        if (isset( $_REQUEST["access_token"])){
            if($_REQUEST["access_token"] != "")
                {
                    $orcidids= getISCAUAIds($access_token, $search);

                    $i = 0;// only 2 records for now

                    foreach($orcidids as $orcidid){
                        $data = getOneInfo($access_token, $orcidid);
                        $person= getPersonInfo($access_token, $orcidid);

                        // only 2 records for now
                        //if($i==2)
                         //   break;
                        $i++;
                    
                        //echo $data['orcid-identifier']['path']  . " | " . $data['person']['name']['given-names']['value'] . " | " . $data['person']['name']['family-name']['value'] . " | " .  count($data['activities-summary']['works']) . "<br>";
                        $works = count($data["group"]);
                        $typeJournal = $typeMagazine = $typeConference = $typeDissertation = $typeBook = $typeOthers = 0;
         
                        //print("<pre>".print_r($person, true)."</pre>");echo "<hr>";

                        if(!is_null($person["affiliation-group"]))
                        if (count($person["affiliation-group"])>0)
                        {
                            echo "<table class='table table-striped'>";
                            echo "<tr style='text-align:left'><th colspan='2'>" . $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["source"]["source-name"]["value"] . "</th><th colspan='2'>" .  $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["role-title"] . "</th><th colspan='2'>" . $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["organization"]["name"]  . "</th><th><a target='_blank' href='https://orcid.org/" . $person["affiliation-group"][0]["summaries"][0]["employment-summary"]["source"]["source-orcid"]["path"] . "'>link</th>";
                        
                            foreach ($data["group"] as $value) {
                                //print("<pre>".print_r($value, true)."</pre>");echo "<hr>";
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

                                // echo $value["work-summary"][0]["publication-date"]["year"]["value"];
                                //echo $value["work-summary"][0]["title"]["title"]["value"] ."<br>";

                            }
                            
                            echo "<tr><th>Total</th><th>Jornal</th><th>Revista</th><th>Conferência</th><th>Dissertação/Tese</th><th>Livro</th><th>Outros</th></tr>";
                            echo "<tr><td>$works</td><td>$typeJournal</td><td>$typeMagazine</td><td>$typeConference</td><td>$typeConference</td><td>$typeBook</td><td>$typeOthers</td></tr>";
                            echo "</table>";
                            echo "<script>$('#userInfo').html('" . count($orcidids) ." users found')</script>";
                        }
                }
             }
        }
        else{
           // $token = authorizeRequest($clientId, $clientSecret);
        }
        ?>

     <!-- </div> results -->
    </div>

    <script>
    const clientId = "APP-3CNS49SKAR5OGR9E";
    const clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
    //getISCAUAIds();

    function authorizeRequest() {

        console.log("Authorizing...");

        var params = {
            "client_id": clientId,
            "client_secret": clientSecret,
            "grant_type": "client_credentials",
            "scope": "/read-public"
        };
        

        if ($("#access_token").val().length < 1) {
            $.ajax({
                   /* headers: {
                        "Access-Control-Allow-Origin": "*",
                        "Access-Control-Allow-Credentials": "true",
                        "Access-Control-Allow-Methods": "GET,HEAD,OPTIONS,POST,PUT",
                        "Access-Control-Allow-Headers": "Origin, X-Requested-With, Content-Type, Accept, Authorization",
                        "Access-Control-Expose-Headers":"Authorization",
                        "Cache-Control": null,
                        "X-Requested-With": null,
                    },*/
                    url: "https://orcid.org/oauth/token",
                    type: 'POST',
                    dataType: "json",
                    data: $.param(params),
                    crossDomain: true
                })
                .done(function(data) {
                    console.log("id_token:" + data.access_token);
                    $("#access_token").val(data.access_token);
                    $("#access_token1").val(data.access_token);
                    $("#refresh_token").val(data.refresh_token);
                    return data.access_token;
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("error");
                });
        }
        else{
            $("#access_token1").val($("#access_token").val());
            console.log($("#access_token").val());
        }
    }

    function getISCAUAIds() {
        var query = document.getElementById("search").value;

        $.ajax({
            type: "GET",
            url: "https://pub.orcid.org/v3.0/search/?q="+query, //affiliation-org-name:(%22Universidade%20de%20Aveiro%20instituto%22)
            dataType: "json",

            error: function(e) {
                alert("An error occurred while processing data");
                console.log("Search failed: ", e);
            },

            success: function(response) {
                console.log("Search received");
                var idArray = [];

                $(response.result).each(function() {
                    var _name = $(this)[0]["orcid-identifier"].path;

                    // add content to the HTML          
                    //$("ul").append('<li onclick="$(&quot;#orcidid&quot;).val(&quot;' + _name +  '&quot;)">ID: ' + _name + '</li>');
                    idArray.push(_name);
                });
                $("#results").val(idArray);

                return response;
            }
        });
    }

    function getORCIDInfo2() {
        var orcidid = $("#orcidid").val();
        var token = $("#access_token").val();

        $.ajax({
            headers: {
                //"Access-Control-Allow-Origin": "*",
                //"Access-Control-Allow-Credentials": "true",
                //"Access-Control-Allow-Methods": "GET,HEAD,OPTIONS,POST,PUT",
                //"Access-Control-Allow-Headers": "Origin, X-Requested-With, Content-Type, Accept, Authorization",
                //"Access-Control-Expose-Headers":"Authorization",
                //'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*',
                //'Cache-Control': 'no-cache',
                "Cache-Control": null,
                "X-Requested-With": null,
                "Content-Type": "application/orcid+json",
                "Authorization" : "Bearer " + token,
                "Access-Control-Allow-Origin" : "*",
                "Accept" : "application/vnd.orcid+json",
                "Access-Control-Allow-Methods" : "POST, GET, OPTIONS, DELETE, PUT",
                "Access-Control-Allow-Headers" : "append,delete,entries,foreach,get,has,keys,set,values,Authorization"
            },
            type: "GET",
            url: "https://api.orcid.org/v3.0/" + orcidid + "/record",
            dataType: "json",

            error: function(e) {
                console.log("Search failed: ", e);
            },

            success: function(response) {
                console.log("Item  received");

                $(response.result).each(function() {
                    var _name = $(this)[0]["orcid-identifier"].path; // URI

                    // add content to the HTML          
                    $("#results").append('<li onclick="$(&quot;#orcidid&quot;).val(' + _name +
                        ')">ID: ' + _name + '</li>');
                });

                return response;
            }
        });
    }

    function getORCIDInfo(orcidid) {

        var access_token = "";

        if ($("#access_token").val().length < 1)
            access_token = authorizeRequest();
        else
            access_token = $("#access_token").val();


        var orcid = "'" + orcidid + "'";
        //orcid = "0000-0002-1976-6538";
        var params = {
            "Bearer": access_token
        };

        $.ajax({
            type: "GET",
            url: "https://api.orcid.org/v3.0/" + orcidid + "/email",
            //url: "https://pub.orcid.org/v3.0/" + orcidid + "/record",
            timeout: 0,
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Accept": "application/vnd.orcid+xml ",
                "Authorization": "Bearer " + access_token
                //"Access-Control-Allow-Methods": "POST, GET, OPTIONS, DELETE, PUT",
                //"Access-Control-Allow-Headers": "append,delete,entries,foreach,get,has,keys,set,values,Authorization"
            },
            // crossDomain: true,
            //data: $.param(params),
            // dataType: "json",

            error: function(e) {
                alert("An error occurred while processing data");
                console.log("Data reading Failed: ", e);
            },

            success: function(response) {
                console.log("Data received");

                $(response.result).each(function() {
                    var _name = $(this)[0]["orcid-identifier"].path; // URI

                    // add content to the HTML          
                    $("#results").append('<li >ID: ' + _name + '</li>');
                });

                return response;
            }
        });
    }
    </script>

</body>

</html>