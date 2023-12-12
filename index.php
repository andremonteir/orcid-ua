<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>ORCID-UA</title>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <form>
    <div class="container">
    <div class="mb-3 row">
        <label for="search" class="form-label">Search&nbsp;</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="affiliation-org-name:(%22Universidade%20de%20Aveiro%20instituto%22)" name="search" id="search">
            <button  class="btn btn-primary mb-3" onclick="getISCAUAIds();">Pesquisar IDs</button>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="access_token1" class="form-label">Token&nbsp;</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="<?php isset($_REQUEST["access_token"]) ? $_REQUEST["access_token"] : "" ?>" name="access_token1" id="access_token1">
            <button class="btn btn-primary mb-3" onclick="authorizeRequest();">Get token</button>
        </div>
    </div>
    <div class="mb-3 row">
        <input type="hidden" value="" name="access_token" id="access_token"><br>
        <input type="hidden" value="" name="refresh_token" id="refresh_token"><br>
        <label for="orcidid" class="form-label">ORCID&nbsp;</label>
        <div class="col-sm-10">
            <input type="text" value="0000-0003-0779-9145" name="orcidid" id="orcidid" class="form-control">
            <input type="submit" value="Get info" class="btn btn-primary mb-3">
        </div>

        <ul></ul>
    </div>
    <div class="mb-3 row" id="results">
    
        <?php

        if(isset( $_REQUEST["access_token"]) && isset( $_REQUEST["orcidid"])){
            $access_token = $_REQUEST["access_token"];
            $orcidid = $_REQUEST["orcidid"];
            $clientId = "APP-3CNS49SKAR5OGR9E"; 
            $clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
            $url = "https://api.orcid.org/v3.0/" . $orcidid . "/record";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                "Content-Type" => "application/orcid+json",
                "Access-Control-Allow-Origin" => "*",
                "Bearer" => $access_token,
                "Access-Control-Allow-Methods" => "POST, GET, OPTIONS, DELETE, PUT",
                "Access-Control-Allow-Headers" => "append,delete,entries,foreach,get,has,keys,set,values,Authorization"
            ];
            
            curl_setopt($curl, CURLOPT_POST, 0);                //0 for a get request
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_HEADER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields, PHP_QUERY_RFC1738));

            $response = curl_exec($curl);
            curl_close($curl);
            echo $response . "<hr>";
        }
        ?>
    </form>
    </div> <!-- results -->
    </div>

    <script>
    const clientId = "APP-3CNS49SKAR5OGR9E";
    const clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
    //getISCAUAIds();

    function authorizeRequest() {

        var params = {
            "client_id": clientId,
            "client_secret": clientSecret,
            "grant_type": "client_credentials",
            "scope": "/read-public"
        };

        if ($("#access_token").val().length < 1) {
            $.ajax({
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
    }

    function getISCAUAIds() {

        $.ajax({
            type: "GET",
            url: "https://pub.orcid.org/v3.0/search/?q=affiliation-org-name:(%22Universidade%20de%20Aveiro%20instituto%22)",
            dataType: "json",

            error: function(e) {
                alert("An error occurred while processing data");
                console.log("Search failed: ", e);
            },

            success: function(response) {
                console.log("Search received");

                $(response.result).each(function() {
                    var _name = $(this)[0]["orcid-identifier"].path;

                    // add content to the HTML          
                    $("ul").append('<li onclick="$(&quot;#orcidid&quot;).val(&quot;' + _name +
                        '&quot;)">ID: ' + _name + '</li>');
                });

                return response;
            }
        });
    }

    function getORCIDInfo2(orcidid) {

        $.ajax({
            headers: {
                "Access-Control-Allow-Origin": "*",
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*',
                'Cache-Control': 'no-cache',
                'Sec-Fetch-Mode': 'navigate'
            },
            type: "GET",
            url: "https://pub.orcid.org/v3.0/" + orcidid,
            data: $.param({
                "orcidid": orcidid
            }),
            dataType: "json",

            error: function(e) {
                //alert("An error occurred while processing data");
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
            "Bearer": $("#access_token").val()
        };

        $.ajax({
            type: "GET",
            url: "https://api.orcid.org/v3.0/" + orcidid + "/email",
            //url: "https://pub.orcid.org/v3.0/" + orcidid + "/record",
            timeout: 0,
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Accept": "application/vnd.orcid+xml ",
                "Bearer": access_token
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