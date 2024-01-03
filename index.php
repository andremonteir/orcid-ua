<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORCID-UA</title>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script type="text/javascript" src="utils.js"></script>
    <style>
        button{width:140px}
    </style>
</head>

<body onload="authorizeRequest()">
    <nav style="height:70px" class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow"><img height="52px"
            src="https://www.ua.pt/imgs/logo_mobile.svg"><a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#"> ORCID
            ISCA-UA</a><img height="52px" src="https://api-assets.ua.pt/files/logos/logo_isca.svg"></nav>
    <br><br><br>
    <?php include "credentials.php"; ?>
    <br><br>
    <div class="container">
        <div class="mb-3 row">
            <div class="col-auto input-group ">
                <span class="input-group-text">Access token</span>
                <input type="text" class="form-control"
                    value="<?php if (isset($_REQUEST["access_token"])) echo $_REQUEST["access_token"]; else echo "" ?>"
                    name="access_token1" id="access_token1" placeholder="Token for ORCID API">
                 <button class="btn btn-primary" onclick="authorizeRequest();">Get new token</button>
            </div>
        </div><hr><br>
        <div class="mb-3 row ">
            <div class="col-auto input-group w-75">
                    <span class="input-group-text">Search params</span>
                    <input class="form-control" type="text" value='<?php echo $searchString ?>' name="search" id="search">
            </div>
            <div class="col-3">
                    <button class="btn btn-primary" onclick="getList()" >Search ORCIDS</button>
                    <button class="btn btn-success" onclick="sendAllToAPI();">Get Search List</button>
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col mt-3 text-center"> <b>OR</b></div>
        </div>
        <div class="mb-3 row ">
            <div class="col-auto input-group w-75">
                <span class="input-group-text">Import CSV</span>
                <input class="form-control  input-sm" type="file" id="csvFileInput" accept=".csv" name="csvFileInput" width="820px" >
            </div>
            <div class="col-3">
                <button class="btn btn-primary" onclick="importCSV()" >Import ORCIDS</button>
                <button class="btn btn-success" onclick="sendAllCSVToAPI()">Get CSV List</button>
            </div>
        </div>
        <form>
        <div class="mb-3 row">
            <input type="hidden" value="c53ec2b9-971d-4139-ae92-38fd4c34462e" name="access_token" id="access_token"><br>
            <input type="hidden" value="f6c18c68-3e29-4ca0-9eb1-6992d5ba1de6" name="refresh_token" id="refresh_token"><br>
        </div>
        </form>

        <table id="myDataTable" class="display" style="width:100%;display:none"></table><br>

        <script>
        var orcidData = []; // Array to store 'orcid' values for later post
        
        $(document).ready(function() {
            $('#myDataTable').DataTable({
                columns: [
                { data: 'name' },
                { data: 'orcid' }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'pdf', 'excel'
                ],
                order: [
                    [1, 'asc']
                ],
                deferRender: true
            });
        });

        </script>
       
        <table id="example" class="display table table-striped" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Organization</th>
                    <th>ORCID</th>
                    <th>Works</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>

</body>

</html>