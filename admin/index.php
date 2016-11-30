<?php
    define('MyConst', TRUE);
    session_start();
    $title = "admin";
    $header = "Louisiana Emergency Response Dispatch";
    $css = array('https://unpkg.com/leaflet@1.0.2/dist/leaflet.css', 'assets/stylesheet.css');
    $js = array('https://unpkg.com/leaflet@1.0.2/dist/leaflet.js');
    include_once '../includes/dbconnect.php';
    include_once '../includes/header.php';
    include_once 'admin_nav.php';

    if (!isset($_SESSION['usr_id'])) { 
        header("Location: login.php");
    }

    $issue_sql = "SELECT id, name, issue_type, issue, X(location), Y(location), created_at FROM Issues";
    $issue_result = $conn->query($issue_sql);
    $issue_map = $conn->query($issue_sql);
    $issue_row = $issue_map->fetch_all();
    $personnel_sql = "SELECT * FROM Personnel";
    $personnel_result = $conn->query($personnel_sql);

    $conn->close();
?>

<div class="container-fluid">
    <div class="row">
        <?php include_once 'sidebar_left.php'; ?>

        <div class="col-sm-9 col-md-10">

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="overview">
                    <?php include_once 'content/overview.php'; ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="emergencies">
                    <?php $result = $issue_result; include_once 'content/emergencies.php'; ?>                    
                </div>
                <div role="tabpanel" class="tab-pane" id="personnel">
                    <?php $result = $personnel_result; include_once 'content/personnel.php'; ?>                    
                </div>
                <div role="tabpanel" class="tab-pane" id="info">
                    <?php include_once 'content/info.php'; ?>
                </div>
            </div>
            
        </div>

    </div>
    
</div>



<?php 
    include_once '../includes/footer.php';
?>

<script type="text/javascript">
    var mymap = L.map('mapid').setView([30.504358, -90.461197], 9);
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: 'cmps394'
    }).addTo(mymap);

    var colorCode = {'fire': 'red', 'medical': 'blue', 'police': 'black', 'other': 'yellow', 'resolved': 'green'};

    var data = <?php echo json_encode($issue_row); ?>;
    for(var i=0;i<data.length;i++) {
        console.log(data[i]);
        var coor = [parseFloat(data[i][4]), parseFloat(data[i][5])];
        console.log(coor);
        L.circleMarker(coor, {radius:10,color:colorCode[data[i][2]]}).addTo(mymap);
    };
    
</script>

</body>
</html>