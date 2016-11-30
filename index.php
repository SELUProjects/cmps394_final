<?php
    define('MyConst', TRUE);
    $header = 'Louisiana Emergency Response';
    $title = 'Issue';
    $css = array('https://unpkg.com/leaflet@1.0.2/dist/leaflet.css', 'resources/stylesheet.css');
    $js = array('https://unpkg.com/leaflet@1.0.2/dist/leaflet.js');
    include_once 'includes/header.php';
    include_once 'includes/navigation.php';
?>



<div class="main">

<h1>Report an Issue</h1>

<h2>Instructions</h2>

<p>
Please enable location services in order to report your issues.
</p>

<!--                                             -->

<?php

include_once 'includes/dbconnect.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['submitissue'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['tel']);
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
    $issueType = mysqli_real_escape_string($conn, $_POST['issueType']);
    $issue = mysqli_real_escape_string($conn, $_POST['issue']);

    // $sql = "INSERT INTO Issues (name, email, phone, location, issue, issue_type) 
    //     VALUES ( '$name', '$email', '$phone', 'Point('$latitude','$longitude', '$issueType', '$issue' )";

    // if ($conn->query($sql) === TRUE) {
    //     echo "New record created successfully";
    // } else {
    //     echo "Error: " . $sql . "<br>" . $conn->error;
    // }

    // $conn->close();
    
    //name can contain only alpha characters and space
    // if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
    //     $error = true;
    //     $name_error = "Name must contain only alphabets and space";
    // }
    // if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
    //     $error = true;
    //     $email_error = "Please Enter Valid Email ID";
    // }
    if (!$error) {
        if(mysqli_query($conn, "INSERT INTO Issues(name,email,phone,location,issue_type,issue) VALUES('" . $name . "', '" . $email . "', '" . $phone . "', POINT(" . $latitude . "," . $longitude . "), '" . $issueType . "', '" . $issue . "')")) {
            $successmsg = "Successful! Help is on the way.";
        } else {
            $errormsg = "Error in registering...Please try again later!";
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="issueform">
                <fieldset>
                    <legend>Submit an issue or emergency</legend>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" name="name" placeholder="Enter Name" value="<?php if($error) echo $name; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($name_error)) echo $name_error; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Email" value="<?php if($error) echo $email; ?>" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="tel">Phone</label>
                        <input type="tel" name="tel" placeholder="Phone" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input id="latitude" type="text" name="latitude" placeholder="Latitude" required class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input id="longitude" type="text" name="longitude" placeholder="Longitude" required class="form-control" />
                    </div>

                    <div class="form-group">
                        <p>Select the type of issue</p>
                        <div class="radio">
                            <label for="fire">
                            <input type="radio" name="issueType" id="fire" value="fire">  
                            Fire</label>
                        </div>
                        <div class="radio">
                            <label for="medical">
                            <input type="radio" name="issueType" id="medical" value="medical"> 
                            Medical</label> 
                        </div>
                        <div class="radio">
                            <label for="police">
                            <input type="radio" name="issueType" id="police" value="police"> 
                            Police</label> 
                        </div>
                        <div class="radio">
                            <label for="other">
                            <input type="radio" name="issueType" id="other" value="other">
                            Other</label> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="issue">What is your issue?</label>
                        <input type="textarea" name="issue" id="issue"  class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="submit" name="submitissue" value="Submit" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
        <div class="col-md-7 col-md-offset-1">
            <div id="mapid" style="height:480px;"></div>
        </div>
    </div>
</div>


<!--                                             -->

<?php
    include_once 'includes/footer.php';
?>

<script>

var x = document.getElementById("longitude");
var y = document.getElementById("latitude");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    var latlon = position.coords.latitude + "," + position.coords.longitude;
    x.value = position.coords.longitude;
    y.value = position.coords.latitude;

    var mymap = L.map('mapid').setView([position.coords.latitude, position.coords.longitude], 9);
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: 'cmps394'
    }).addTo(mymap);

    L.marker([position.coords.latitude, position.coords.longitude]).addTo(mymap);
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
window.onload = function() {
    getLocation();
}
</script>

</body>

</html>
