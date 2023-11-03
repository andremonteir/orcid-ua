<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <h1>ISCA-UA ORCID Summary</h1>
        <br>
        <pre id="result">...</pre>
        <form action="" method="POST">
			<br /><br />
            <input class="form-control" type="text" id="token" name="token" placeholder="tokenid">
            <input class="form-control" type="text" id="bearer" name="bearer" placeholder="bearer">
            <input class="form-control" type="text" id="search" name="search" placeholder="search items" value="demeranville">
            <input class="btn btn-info" type="submit" value="pesquisar" name="submit">
		</form>  
        
        <?php
		
        $redirectUri = "https://wonderful-pebble-03a26b403.3.azurestaticapps.net";
        $client_id = "APP-3CNS49SKAR5OGR9E"; 
        $client_secret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
        $authEndpoint = "https://orcid.org/oauth/authorize/";
        $tokenEndpoint = "https://orcid.org/oauth/token/";
        $url = "https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=" . redirectUri . $client_id . "&scope=openid";
        // https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=http%3A%2F%2Flocalhost%3A&client_id=APP-3CNS49SKAR5OGR9E&scope=openid"
        $searchURL = "https://pub.orcid.org/v1.2/search/orcid-bio/?q=";

		if (isset($_POST['submit']) && $_POST['submit']!="") {

            if(isset($_REQUEST['token'])){

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

                $url = tokenEndpoint . $token;
                $client = curl_init($url);
                $payload = json_encode( array( "client_id"=> $client_id,  "client_secret"=> $client_secret,"grant_type"=> "client_credentials","scope"=> "scope") );
                curl_setopt($client, CURLOPT_POSTFIELDS, $payload );
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($client);
                $result = json_decode($response);

                print_r($result);
            }
		}
			
			
		?>
       
        <script>

      

        </script>
    </body>
</html>