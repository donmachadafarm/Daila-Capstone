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
                Made-To-Order Job Order Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" id="txtDateMax" name="startDate">
                <input type="date" id="endPicker" name="endDate">
                <input type="submit" name="search"><br />
                <input type="submit" name="Monthly" value="Monthly">
                <input type="submit" name="Quarterly" value="Quarterly">
                <input type="submit" name="Yearly" value="Yearly">

            </form>
            <h5 class="text-center">*Click on the ID number for more details*</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Job Order ID</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Due Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $result = mysqli_query($conn, "SELECT *
                                                    FROM joborder
                                                    WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND joborder.type = 'Made to Order'
                                                    AND joborder.status = 'Finished'
                                                    ORDER BY jobOrder.dueDate ASC");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                    FROM joborder
                                                    WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND joborder.type = 'Made to Order'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Transactions between ';
                        echo $startDate;
                        echo ' and ';
                        echo $endDate;
                        echo '</h2><br>';

                        while ($row = mysqli_fetch_array($result2)) {
                            $sum = $row['sum'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['orderID'];
                            $name = $row['customerID'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];
                            $ddate = $row['dueDate'];
                            $status = $row['status'];

                            echo '<tr>';

                                echo '<td class="text-center">';
                                    echo '<a href="detailsJO.php?id='.$id.'">';
                                        echo $id;
                                    echo '</a>';
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo get_customerName($conn,$name);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $date;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $ddate;
                                echo '</td>';

                                echo '<td class="text-center">';
                                     echo number_format($totalPrice, 2);
                                echo '</td>';

                                echo '<td class="text-center">';
                                     echo $status;
                                echo '</td>';

                            echo '</tr>';

                            $dataArr[] = $id;
                        }
                        unset($_SESSION['reportMTS']);
                        $_SESSION['reportMTS'] = $dataArr;

                        echo '</tbody>';
                        echo '</table>';
                        echo '<br />';

                        echo '<div class="container">';
                          echo '<div class="row">';
                            echo "<div class='col-lg-6'>";
                              echo "<div class=text-left>";
                                echo "<a href='printMTO.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
                              echo "</div>";
                            echo "</div>";
                            echo '<div class="col-lg-6">';
                              echo "<div class='text-right'>";
                                echo '<h4 class="text-right">Total Revenue: ';
                                  echo number_format($sum, 2);
                                echo '</h4>';
                              echo "</div>";
                            echo '</div>';
                          echo '</div>';
                        echo '</div>';
                        echo "<br /><br />";

                    }
                }elseif(isset($_POST['Monthly'])){
                  $startDate = date("Y-n-j", strtotime("first day of previous month"));
                  $endDate = date("Y-n-j", strtotime("last day of previous month"));

                  $result = mysqli_query($conn, "SELECT *
                                                  FROM joborder
                                                  WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                  AND joborder.type = 'Made to Order'
                                                  AND joborder.status = 'Finished'
                                                  ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                  AND joborder.type = 'Made to Order'");

                  if ($count == "0"){
                      echo "<h2 class='text-center'>No transactions within $startDate and $endDate</h2>";
                  }
                  else {

                      echo '<h2 class="text-center">Transactions between ';
                      echo $startDate;
                      echo ' and ';
                      echo $endDate;
                      echo '</h2><br>';

                      while ($row = mysqli_fetch_array($result2)) {
                          $sum = $row['sum'];
                      }

                      while ($row = mysqli_fetch_array($result)) {

                          $id = $row['orderID'];
                          $name = $row['customerID'];
                          $totalPrice = $row['totalPrice'];
                          $date = $row['orderDate'];
                          $ddate = $row['dueDate'];
                          $status = $row['status'];

                          echo '<tr>';

                              echo '<td class="text-center">';
                                  echo '<a href="detailsJO.php?id='.$id.'">';
                                      echo $id;
                                  echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo get_customerName($conn,$name);
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $date;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $ddate;
                              echo '</td>';

                              echo '<td class="text-center">';
                                   echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                                   echo $status;
                              echo '</td>';

                          echo '</tr>';

                          $dataArr[] = $id;
                      }
                      unset($_SESSION['reportMTS']);
                      $_SESSION['reportMTS'] = $dataArr;

                      echo '</tbody>';
                      echo '</table>';
                      echo '<br />';

                      echo '<div class="container">';
                        echo '<div class="row">';
                          echo "<div class='col-lg-6'>";
                            echo "<div class=text-left>";
                              echo "<a href='printMTO.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
                            echo "</div>";
                          echo "</div>";
                          echo '<div class="col-lg-6">';
                            echo "<div class='text-right'>";
                              echo '<h4 class="text-right">Total Revenue: ';
                                echo number_format($sum, 2);
                              echo '</h4>';
                            echo "</div>";
                          echo '</div>';
                        echo '</div>';
                      echo '</div>';
                      echo "<br /><br />";

                  }
                }elseif(isset($_POST['Quarterly'])){
                  $q = array();
                  $dt = new DateTime();
                  $dt->modify("-4 months");
                  $q[] = getQuarter($dt);

                  $startDate = $q[0]['start'];
                  $endDate = $q[0]['end'];

                  $result = mysqli_query($conn, "SELECT *
                                                  FROM joborder
                                                  WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                  AND joborder.type = 'Made to Order'
                                                  AND joborder.status = 'Finished'
                                                  ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                  AND joborder.type = 'Made to Order'");

                  if ($count == "0"){
                      echo "<h2 class='text-center'>No transactions within $startDate and $endDate</h2>";
                  }
                  else {

                      echo '<h2 class="text-center">Transactions between ';
                      echo $startDate;
                      echo ' and ';
                      echo $endDate;
                      echo '</h2><br>';

                      while ($row = mysqli_fetch_array($result2)) {
                          $sum = $row['sum'];
                      }

                      while ($row = mysqli_fetch_array($result)) {

                          $id = $row['orderID'];
                          $name = $row['customerID'];
                          $totalPrice = $row['totalPrice'];
                          $date = $row['orderDate'];
                          $ddate = $row['dueDate'];
                          $status = $row['status'];

                          echo '<tr>';

                              echo '<td class="text-center">';
                                  echo '<a href="detailsJO.php?id='.$id.'">';
                                      echo $id;
                                  echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo get_customerName($conn,$name);
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $date;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $ddate;
                              echo '</td>';

                              echo '<td class="text-center">';
                                   echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                                   echo $status;
                              echo '</td>';

                          echo '</tr>';

                          $dataArr[] = $id;
                      }
                      unset($_SESSION['reportMTS']);
                      $_SESSION['reportMTS'] = $dataArr;

                      echo '</tbody>';
                      echo '</table>';
                      echo '<br />';

                      echo '<div class="container">';
                        echo '<div class="row">';
                          echo "<div class='col-lg-6'>";
                            echo "<div class=text-left>";
                              echo "<a href='printMTO.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
                            echo "</div>";
                          echo "</div>";
                          echo '<div class="col-lg-6">';
                            echo "<div class='text-right'>";
                              echo '<h4 class="text-right">Total Revenue: ';
                                echo number_format($sum, 2);
                              echo '</h4>';
                            echo "</div>";
                          echo '</div>';
                        echo '</div>';
                      echo '</div>';
                      echo "<br /><br />";

                  }
                }elseif(isset($_POST['Yearly'])){
                  $year = date('Y') - 1; // Get current year and subtract 1
                  $start = mktime(0, 0, 0, 1, 1, $year);
                  $end = mktime(0, 0, 0, 12, 31, $year);

                  $startDate = date('m/d/Y',$start);
                  $endDate = date('m/d/Y',$end);


                  $result = mysqli_query($conn, "SELECT *
                                                  FROM joborder
                                                  WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                  AND joborder.type = 'Made to Order'
                                                  AND joborder.status = 'Finished'
                                                  ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                  AND joborder.type = 'Made to Order'");

                  if ($count == "0"){
                      echo "<h2 class='text-center'>No transactions within $startDate and $endDate</h2>";
                  }
                  else {

                      echo '<h2 class="text-center">Transactions between ';
                      echo $startDate;
                      echo ' and ';
                      echo $endDate;
                      echo '</h2><br>';

                      while ($row = mysqli_fetch_array($result2)) {
                          $sum = $row['sum'];
                      }

                      while ($row = mysqli_fetch_array($result)) {

                          $id = $row['orderID'];
                          $name = $row['customerID'];
                          $totalPrice = $row['totalPrice'];
                          $date = $row['orderDate'];
                          $ddate = $row['dueDate'];
                          $status = $row['status'];

                          echo '<tr>';

                              echo '<td class="text-center">';
                                  echo '<a href="detailsJO.php?id='.$id.'">';
                                      echo $id;
                                  echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo get_customerName($conn,$name);
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $date;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $ddate;
                              echo '</td>';

                              echo '<td class="text-center">';
                                   echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                                   echo $status;
                              echo '</td>';

                          echo '</tr>';

                          $dataArr[] = $id;
                      }
                      unset($_SESSION['reportMTS']);
                      $_SESSION['reportMTS'] = $dataArr;

                      echo '</tbody>';
                      echo '</table>';
                      echo '<br />';

                      echo '<div class="container">';
                        echo '<div class="row">';
                          echo "<div class='col-lg-6'>";
                            echo "<div class=text-left>";
                              echo "<a href='printMTO.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
                            echo "</div>";
                          echo "</div>";
                          echo '<div class="col-lg-6">';
                            echo "<div class='text-right'>";
                              echo '<h4 class="text-right">Total Revenue: ';
                                echo number_format($sum, 2);
                              echo '</h4>';
                            echo "</div>";
                          echo '</div>';
                        echo '</div>';
                      echo '</div>';
                      echo "<br /><br />";

                  }
                }else{
                    $result = mysqli_query($conn, "SELECT * FROM `joborder`
                                                    WHERE joborder.type = 'Made to ORder' AND joborder.status = 'finished'
                                                    ORDER BY `type` ASC");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                    FROM joborder
                                                    WHERE joborder.type = 'Made to Order'
                                                    AND joborder.status='Finished'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">There are no transactions yet</h2>';
                    }
                    else {

                        while ($row = mysqli_fetch_array($result2)) {
                            $sum = $row['sum'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['orderID'];
                            $name = $row['customerID'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];
                            $ddate = $row['dueDate'];
                            $status = $row['status'];

                            echo '<tr>';

                                echo '<td class="text-center">';
                                    echo '<a href="detailsJO.php?id='.$id.'">';
                                        echo $id;
                                    echo '</a>';
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo get_customerName($conn,$name);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $date;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $ddate;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo number_format($totalPrice);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $status;
                                echo '</td>';

                            echo '</tr>';

                            $dataArr[] = $id;

                        }
                        unset($_SESSION['reportMTS']);
                        $_SESSION['reportMTS'] = $dataArr;

                        echo '</tbody>';
                        echo '</table>';
                        echo '<br />';

                        echo '<div class="container">';
                          echo '<div class="row">';
                            echo "<div class='col-lg-12'>";
                              echo "<div class=text-center>";
                                echo "<a href='printMTO.php' class='btn btn-primary'>Print this report</a>";
                              echo "</div>";
                            echo "</div>";
                            // echo '<div class="col-lg-6">';
                              // echo "<div class='text-right'>";
                                // echo '<h4 class="text-right">Total Revenue: ';
                                  // echo number_format($sum, 2);
                                // echo '</h4>';
                              // echo "</div>";
                            // echo '</div>';
                          echo '</div>';
                        echo '</div>';
                        echo "<br /><br />";
                    }
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
