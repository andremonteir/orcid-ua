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
            <input class="form-control" type="text" id="token" placeholder="tokenid">
            <input class="form-control" type="text" id="search" placeholder="search items" value="demeranville">
            <input class="btn btn-info" type="submit" value="pesquisar" name="submit">
		</form>  
        
        <?php
		
        $redirectUri = "https://wonderful-pebble-03a26b403.3.azurestaticapps.net";
        $clientId = "APP-3CNS49SKAR5OGR9E"; 
        $clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";
        $authEndpoint = "https://orcid.org/oauth/authorize/";
        $tokenEndpoint = "https://orcid.org/oauth/token/";
        $url = "https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=http%3A%2F%2Flocalhost%3A&client_id=" . $clientId . "&scope=openid";
        // https://qa.orcid.org/oauth/authorize?response_type=token&redirect_uri=http%3A%2F%2Flocalhost%3A&client_id=APP-3CNS49SKAR5OGR9E&scope=openid"

		if (isset($_POST['submit']) && $_POST['submit']!="") {

            if(isset($_REQUEST['token'])){

                $token = $_REQUEST['token'];
                $url = tokenEndpoint . $token;
                $client = curl_init($url);
                
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($client);
                $result = json_decode($response);
                
                if(isset($result)){
                    echo "<table class='table table-striped'>";
                    echo "<tr><td>Order ID:</td><td>$result->order_id</td></tr>";
                    echo "<tr><td>Amount:</td><td>$result->amount</td></tr>";
                    echo "<tr><td>Response Code:</td><td>$result->response_code</td></tr>";
                    echo "<tr><td>Response Desc:</td><td>$result->response_desc</td></tr>";
                    echo "</table>";
                }
            }
            else{

            }
		}
			
			
		?>
       
        <script>

      

        </script>
    </body>
</html>