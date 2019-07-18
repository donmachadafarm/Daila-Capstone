<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}

$dataArr = array();
?>

<!-- put all the contents here  -->

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                Machine Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6><br>
            <form method="post" class="text-center">
              <div class="row justify-content-md-center">
                <div class="col-2">
                  From:<input class="form-control" type="date" id="txtDateMax" name="startDate">
                </div>
                <div class="col-2">
                  To:<input class="form-control" type="date" id="endPicker" name="endDate"><br>
                </div>
              </div>
                <input class="btn btn-primary" type="submit" name="search">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Machine Name</th>
                    <th>Transaction Date</th>
                    <th>Transaction Cost</th>
                    <th>Problem Identified</th>
                    <th>Solution</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];

                    $result = mysqli_query($conn, "SELECT * FROM MaintenanceTransaction WHERE maintenanceDate
                                                    BETWEEN '$startDate' AND '$endDate' ORDER BY transactionID DESC");

                    $count = 0;
                        while ($row = mysqli_fetch_array($result)) {

                            $tranid = $row['transactionID'];
                            $machid = $row['machineID'];
                            $cost = $row['maintenanceCost'];
                            $date = $row['maintenanceDate'];
                            $prob = $row['problemIdentified'];
                            $sol = $row['solution'];


                            echo '<tr>';

                                echo '<td class="text-center">';
                                    echo "Transaction ID: " . $tranid;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo get_machinename($conn,$machid);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $date;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo number_format($cost);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $prob;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $sol;
                                echo '</td>';

                            echo '</tr>';

                            $dataArr[$count]['tranid'] = $tranid;
                            $dataArr[$count]['machid'] = $machid;
                            $dataArr[$count]['cost'] = $cost;
                            $dataArr[$count]['date'] = $date;
                            $dataArr[$count]['prob'] = $prob;
                            $dataArr[$count]['sol'] = $sol;

                            $count++;

                            $new = http_build_query(array('data' => $dataArr));
                        }
                        echo "<div class='col-lg-12'>";
                          echo "<div class=text-center>";
                            echo "<a href='printMachine.php?$new&start=$startDate&end=$endDate' class='btn btn-success'>Print this report</a>";
                          echo "</div>";
                        echo "</div>";
                }else{
                    $result = mysqli_query($conn, "SELECT * FROM MaintenanceTransaction ORDER BY transactionID DESC");
                    $count = 0;
                        while ($row = mysqli_fetch_array($result)) {

                            $tranid = $row['transactionID'];
                            $machid = $row['machineID'];
                            $cost = $row['maintenanceCost'];
                            $date = $row['maintenanceDate'];
                            $prob = $row['problemIdentified'];
                            $sol = $row['solution'];


                            echo '<tr>';

                                echo '<td class="text-center">';
                                    echo "Transaction ID: " . $tranid;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo get_machinename($conn,$machid);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $date;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $cost;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $prob;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $sol;
                                echo '</td>';

                            echo '</tr>';

                            $dataArr[$count]['tranid'] = $tranid;
                            $dataArr[$count]['machid'] = $machid;
                            $dataArr[$count]['cost'] = $cost;
                            $dataArr[$count]['date'] = $date;
                            $dataArr[$count]['prob'] = $prob;
                            $dataArr[$count]['sol'] = $sol;

                            $count++;

                            $new = http_build_query(array('data' => $dataArr));
                        }
                        echo "<div class='col-lg-12'>";
                          echo "<div class=text-center>";
                            echo "<a href='printMachine.php?$new' class='btn btn-success'>Print this report</a>";
                          echo "</div>";
                        echo "</div>";
                }

                ?>

        </div>
    </div>
</div>

<script>
    document.getElementById('txtDateMax').onchange = function () {
        document.getElementById('endPicker').setAttribute('min',  this.value);
    };
</script>
