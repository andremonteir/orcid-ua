// ANdré Monteiro ORCID API keys
const clientId = "APP-3CNS49SKAR5OGR9E";
const clientSecret = "a37f23e3-b58f-4a99-9ea6-2820868f6610";


function getList() {
    var query = document.getElementById("search").value;

    // Pass a callback function to getISCAUAIds
    getISCAUAIds(query, function (orcidids) {
        $('#myDataTable').DataTable().clear();
        $('#myDataTable').DataTable().rows.add(orcidids).draw();

        orcidData = orcidids.map(function (row) {
            return row.orcid;
        });

        $('#myDataTable').show();
    });
}

function importCSV() {
    var fileInput = document.getElementById("csvFileInput");

    // Handle the file input change event
    var file = fileInput.files[0];

    if (file) {
        Papa.parse(file, {
            header: true,
            dynamicTyping: true,
            delimiter: ';', // Specify the delimiter
            complete: function (results) {
                // Clear existing data
                $('#myDataTable').DataTable().clear();
                // Add new data
                $('#myDataTable').DataTable().rows.add(results.data).draw();

                // Update orcidData array with 'orcid' values
                orcidData = results.data.map(function (row) {
                    return row.orcid;
                });
                $('#myDataTable').show();
            }
        });
    }
}

function sendAllCSVToAPI() {
    // Use the orcidData array to send all 'orcid' values to your API

    var access_token = "";
    var search = orcidData;

    if ($("#access_token1").val().length < 1)
        access_token = authorizeRequest();
    else
        access_token = $("#access_token1").val();

    var table = $('#example').DataTable();
    table.destroy();

    table = new DataTable('#example', {
        ajax: {
            url: "getPeopleCSV.php",
            method: 'POST',
            dataSrc: '',
            data: function (d) {
                d.access_token = access_token;
                d.q = search;
            }
        },
        processing: true,
        columns: [
            { data: null, defaultContent: '', className: 'dt-control details-btn', orderable: false },
            { data: 'name' },
            { data: 'role' },
            { data: 'organization' },
            { data: 'orcid' },
            { data: 'work_count' }
        ],
        order: [
            [1, 'asc']
        ],
        rowId: 'orcid',
        drawCallback: function (settings) {
            var api = this.api();

            // Handle the click event on the "details-btn"
            api.$('td.details-btn').unbind('click').on('click', function () {
                var tr = $(this).closest('tr');
                var row = api.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child('Loading...').show(); // Placeholder while details are being fetched

                    // Perform the API call
                    $.ajax({
                        url: 'getPersonDetail.php?access_token=' + access_token + "&q=" + row.data().orcid,
                        method: 'GET',
                        dataType: 'json',
                        success: function (details) {

                            try {

                                if (details && Array.isArray(details) && details.length > 0) {
                                    var detailsTable = '<table class="details-table table table-sm" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
                                    detailsTable += '<thead><tr><th>Year</th><th>Title</th><th>Journal/Conference</th><th>URL</th></tr></thead>';

                                    for (let i = 0; i < details.length; i++) {
                                        detailsTable += '<tr>' +
                                            '<td>' + details[i].year + '</td>' +
                                            '<td>' + details[i].title + '</td>' +
                                            '<td>' + details[i].journal + '</td>' +
                                            '<td><a target="_blank" href="' + details[i].url + '">Link</a></td>' +
                                            '</tr>';
                                    }

                                    detailsTable += '</table>';
                                } else {
                                    detailsTable = '<span>No results</span>';
                                }

                                // Update the details content
                                row.child(detailsTable, 'details-row').show();
                                tr.addClass('shown');
                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                            }
                        },

                        error: function (error) {
                            console.error('Error fetching details: ', error);
                        }

                    });
                }
            });
        }
    });
}

function sendAllToAPI() {
    var access_token = "";
    var search = 'affiliation-org-name:(%22Universidade%20de%20Aveiro%20instituto%20Superior%22)+OR+current-institution-affiliation-name:(%22Institute%20of%20Higher%20Learning%20in%20Accounting%20and%20Administration%22)';

    if ($("#access_token1").val().length < 1)
        access_token = authorizeRequest();
    else
        access_token = $("#access_token1").val();

    if ($("#search").val().length > 1)
        search = $("#search").val();

    var table = $('#example').DataTable();
    table.destroy();

    table = new DataTable('#example', {
        ajax: {
            url: "getPeople.php",
            method: 'POST',
            dataSrc: '',
            data: function (d) {
                d.access_token = access_token;
                d.q = search;
            }
        },
        processing: true,
        columns: [
            { data: null, defaultContent: '', className: 'dt-control details-btn', orderable: false },
            { data: 'name' },
            { data: 'role' },
            { data: 'organization' },
            { data: 'orcid' },
            { data: 'work_count' }
        ],
        order: [
            [1, 'asc']
        ],
        rowId: 'orcid',
        drawCallback: function (settings) {
            var api = this.api();

            // Handle the click event on the "details-btn"
            api.$('td.details-btn').unbind('click').on('click', function () {
                var tr = $(this).closest('tr');
                var row = api.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child('Loading...').show(); // Placeholder while details are being fetched

                    // Perform the API call
                    $.ajax({
                        url: 'getPersonDetail.php?access_token=' + access_token + "&q=" + row.data().orcid,
                        method: 'GET',
                        dataType: 'json',
                        success: function (details) {

                            try {

                                if (details && Array.isArray(details) && details.length > 0) {
                                    var detailsTable = '<table class="details-table table table-sm" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
                                    detailsTable += '<thead><tr><th>Year</th><th>Title</th><th>Journal/Conference</th><th>URL</th></tr></thead>';

                                    for (let i = 0; i < details.length; i++) {
                                        detailsTable += '<tr>' +
                                            '<td>' + details[i].year + '</td>' +
                                            '<td>' + details[i].title + '</td>' +
                                            '<td>' + details[i].journal + '</td>' +
                                            '<td><a target="_blank" href="' + details[i].url + '">Link</a></td>' +
                                            '</tr>';
                                    }

                                    detailsTable += '</table>';
                                } else {
                                    detailsTable = '<span>No results</span>';
                                }

                                // Update the details content
                                row.child(detailsTable, 'details-row').show();
                                tr.addClass('shown');
                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                            }
                        },

                        error: function (error) {
                            console.error('Error fetching details: ', error);
                        }

                    });
                }
            });
        }
    });

}


function authorizeRequest() {
    console.log("Authorizing...");
  
    var params = {
      "client_id": clientId,
      "client_secret": clientSecret,
      "grant_type": "client_credentials",
      "scope": "/read-public"
    };
  
    if ($("#access_token1").val().length < 1) {
      // Use a promise to handle the asynchronous AJAX request
      return new Promise(function(resolve, reject) {
        $.ajax({
          url: "https://orcid.org/oauth/token",
          type: 'POST',
          dataType: "json",
          data: $.param(params),
          headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Origin": "http://localhost:8080",
            "Access-Control-Allow-Headers": "Origin, X-Requested-With, Content-Type, Accept"
        },
          crossDomain: true
        })
        .done(function(data) {
          console.log("id_token:" + data.access_token);
          $("#access_token").val(data.access_token);
          $("#access_token1").val(data.access_token);
          $("#refresh_token").val(data.refresh_token);
          resolve(data.access_token);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log("error");
          reject(errorThrown);
        });
      });
    } else {
      $("#access_token1").val($("#access_token").val());
      console.log($("#access_token").val());
      // Return a resolved promise with the existing access token
      return Promise.resolve($("#access_token").val());
    }
  }

function authorizeRequest2() {

    console.log("Authorizing...");

    var params = {
        "client_id": clientId,
        "client_secret": clientSecret,
        "grant_type": "client_credentials",
        "scope": "/read-public"
    };


    if ($("#access_token1").val().length < 1) {
        $.ajax({
            url: "https://orcid.org/oauth/token",
            type: 'POST',
            dataType: "json",
            data: $.param(params),
            crossDomain: true
        })
            .done(function (data) {
                console.log("id_token:" + data.access_token);
                $("#access_token").val(data.access_token);
                $("#access_token1").val(data.access_token);
                $("#refresh_token").val(data.refresh_token);
                return data.access_token;
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("error");
            });
    } else {
        $("#access_token1").val($("#access_token").val());
        console.log($("#access_token").val());
    }
}

function getISCAUAIds(query, callback) {
    var idArray = [];

    $.ajax({
        type: "GET",
        url: "https://pub.orcid.org/v3.0/search/?q=" + query,
        dataType: "json",
        success: function (response) {
            console.log("Search received");

            $(response.result).each(function () {
                var _orcid = $(this)[0]["orcid-identifier"].path;
                idArray.push({ name: "no data", orcid: _orcid });
            });

            // Call the callback function with the populated idArray
            callback(idArray);
        },
        error: function (e) {
            console.log("Search failed: ", e);
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

        error: function (e) {
            alert("An error occurred while processing data");
            console.log("Data reading Failed: ", e);
        },

        success: function (response) {
            console.log("Data received");

            $(response.result).each(function () {
                var _name = $(this)[0]["orcid-identifier"].path; // URI

                // add content to the HTML          
                $("#results").append('<li >ID: ' + _name + '</li>');
            });

            return response;
        }
    });
}