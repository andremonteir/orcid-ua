<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>ISCA-UA ORCID </h1>
        <br>
        <pre id="result">...</pre>
        <form action="" method="POST">
			<br /><br />
            <input class="form-control" type="text" id="token" name="token" placeholder="tokenid"><br>
            <input class="form-control" type="text" id="bearer" name="bearer" placeholder="bearer"><br>
            <input class="form-control" type="text" id="search" name="search" placeholder="search items" value="universidade de aveiro isca"><br>
            <input class="btn btn-info" type="submit" value="submit" name="submit"><br>
		</form>  
        
        <?php
		
        $redirectUri = "https://wonderful-pebble-03a26b403.3.azurestaticapps.net";
        $client_id = "APP-3CNS49SKAR5OGR9E"; 
        $client_secret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
        $authEndpoint = "https://orcid.org/oauth/authorize/";
        $tokenEndpoint = "https://orcid.org/oauth/token/";
        $url = "https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=" . $redirectUri . $client_id . "&scope=openid";
        // https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=https://wonderful-pebble-03a26b403.3.azurestaticapps.net&client_id=APP-3CNS49SKAR5OGR9E&scope=openid"
        $searchURL = "https://pub.orcid.org/v3.0/search/?q=";

		if (isset($_POST['submit']) && $_POST['submit']!="") {

            if(isset($_REQUEST['token'])&& $_POST['token']!=""){

                echo "has token";
                $bearer = $_POST['bearer'];
                $search = $_POST['search'];
                $token = $_REQUEST['token'];
                $url = $searchURL;
                $client = curl_init($url);
                
                $payload = json_encode( array( "Bearer"=> $bearer) );
                curl_setopt($client, CURLOPT_POSTFIELDS, $payload );
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($client);
                $result = json_decode($response);
                
                echo "<table class='table table-striped'>";

                if(isset($result)){
                   
                    echo "<tr><td>ORCID ID</td><td>$result->uri</td></tr>";
                }
                echo "</table>";

            }
            else{
                echo "no token";
                $url = $tokenEndpoint;
                $client = curl_init($url);
                $payload = json_encode( array( "client_id"=> $client_id,  "client_secret"=> $client_secret,"grant_type"=> "client_credentials","scope"=> "scope") );
                curl_setopt($client, CURLOPT_NOBODY, 1);
                curl_setopt($client, CURLOPT_POSTFIELDS, $payload );
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($client);
                echo $response;
                $result = json_decode($response);
                echo "<br>no token22";
                print_r($result);
            }
		}
        else
         echo "no nothing";
			
			
		?>
       
        <script>

      

        </script>
    </body>
</html>