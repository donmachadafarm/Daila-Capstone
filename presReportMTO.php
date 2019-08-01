<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}

$thisMonth = date('n');
$thisYear = date('Y');

if (isset($_POST["month"])) {
  $month = $_POST["month"];
}else{
    $month = $thisMonth;
}

if (isset($_POST["year"])) {
  $year = $_POST["year"];
}else{
    $year = $thisYear;
}
?>

<!-- put all the contents here  -->

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                Made-To-Order Job Order Report
            </h1>

            <h6 class="text-center">
                Enter a Month/Quarter and Year
            </h6>
            
            <form method="post" class="text-center">
              <div class="row">
              <div class="col-md-2">
              </div>
                <div class="col-md-3">
                  <select class="form-control" id="chooseMonth" name="month">
                    <option value="1" <?php if($month == '1') { ?> selected <?php } ?>>January</option>
                    <option value="2" <?php if($month == '2') { ?> selected <?php } ?>>February</option>
                    <option value="3" <?php if($month == '3') { ?> selected <?php } ?>>March</option>                      
                    <option value="4" <?php if($month == '4') { ?> selected <?php } ?>>April</option>
                    <option value="5" <?php if($month == '5') { ?> selected <?php } ?>>May</option>
                    <option value="6" <?php if($month == '6') { ?> selected <?php } ?>>June</option>
                    <option value="7" <?php if($month == '7') { ?> selected <?php } ?>>July</option>
                    <option value="8" <?php if($month == '8') { ?> selected <?php } ?>>August</option>
                    <option value="9" <?php if($month == '9') { ?> selected <?php } ?>>September</option>
                    <option value="10" <?php if($month == '10') { ?> selected <?php } ?>>October</option>
                    <option value="11" <?php if($month == '11') { ?> selected <?php } ?>>November</option>
                    <option value="12" <?php if($month == '12') { ?> selected <?php } ?>>December</option>
                    <option value="13" <?php if($month == '13') { ?> selected <?php } ?>>Quarter 1</option>
                    <option value="14" <?php if($month == '14') { ?> selected <?php } ?>>Quarter 2</option>
                    <option value="15" <?php if($month == '15') { ?> selected <?php } ?>>Quarter 3</option>
                    <option value="16" <?php if($month == '16') { ?> selected <?php } ?>>Quarter 4</option>
                    <option value="17" <?php if($month == '17') { ?> selected <?php } ?>>Year</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <select class="form-control" id="chooseYear" name="year">
                    <option value="2018" <?php if($year == '2018') { ?> selected <?php } ?>>2018</option>
                    <option value="2019" <?php if($year == '2019') { ?> selected <?php } ?>>2019</option>
                  </select>
                </div>
              <div class="col-md-2">
                <input class="btn btn-success" type="submit" name="search">
              </div>
            </div>
            </form>
          </div>
        </div>
            
            <h5 class="text-center">*Click on the ID number for more details*</h5>
        
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Job Order ID</th>
                    <th>Customer</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                  //QUARTER 1
                    if($month==13){
                      $year = $_POST['year'];
                      $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                    customer.company AS cName,
                                                    joborder.orderDate AS orderDate,
                                                    joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE MONTH(joborder.orderDate) = 1
                                                    OR MONTH(joborder.orderDate) = 2
                                                    OR MONTH(joborder.orderDate) = 3
                                                    AND YEAR(joborder.orderDate) = $year
                                                    AND joborder.type = 'Made to Order'
                                                    AND joborder.status = 'Finished'
                                                    ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE MONTH(joborder.orderDate) = $month
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                      else {

                          echo '<h2 class="text-center">Transactions during ';
                          echo 'January to March';
                          echo ' ';
                          echo $year;
                          echo '</h2><br>';

                          while ($row = mysqli_fetch_array($result2)) {
                              $sum = $row['sum'];
                          }

                          while ($row = mysqli_fetch_array($result)) {

                              $id = $row['JOID'];
                              $name = $row['cName'];
                              $totalPrice = $row['totalPrice'];
                              $date = $row['orderDate'];

                              echo '<tr>';

                              echo '<td class="text-center">';
                              echo '<a href="detailsJO.php?id='.$id.'">';
                              echo $id;
                              echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $name;
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $date;
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
                                  // echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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
                    }
                    elseif($month == 14){
                      $year = $_POST['year'];
                      $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                    customer.company AS cName,
                                                    joborder.orderDate AS orderDate,
                                                    joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE MONTH(joborder.orderDate) = 4
                                                    OR MONTH(joborder.orderDate) = 5
                                                    OR MONTH(joborder.orderDate) = 6
                                                    AND YEAR(joborder.orderDate) = $year
                                                    AND joborder.type = 'Made to Order'
                                                    AND joborder.status = 'Finished'
                                                    ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE MONTH(joborder.orderDate) = $month
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                      else {

                          echo '<h2 class="text-center">Transactions during ';
                          echo 'April to June';
                          echo ' ';
                          echo $year;
                          echo '</h2><br>';

                          while ($row = mysqli_fetch_array($result2)) {
                              $sum = $row['sum'];
                          }

                          while ($row = mysqli_fetch_array($result)) {

                              $id = $row['JOID'];
                              $name = $row['cName'];
                              $totalPrice = $row['totalPrice'];
                              $date = $row['orderDate'];

                              echo '<tr>';

                              echo '<td class="text-center">';
                              echo '<a href="detailsJO.php?id='.$id.'">';
                              echo $id;
                              echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $name;
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $date;
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
                                  // echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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
                    }
                    elseif($month == 15){
                      $year = $_POST['year'];
                      $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                    customer.company AS cName,
                                                    joborder.orderDate AS orderDate,
                                                    joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE MONTH(joborder.orderDate) = 7
                                                    OR MONTH(joborder.orderDate) = 8
                                                    OR MONTH(joborder.orderDate) = 9
                                                    AND YEAR(joborder.orderDate) = $year
                                                    AND joborder.type = 'Made to Order'
                                                    AND joborder.status = 'Finished'
                                                    ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE MONTH(joborder.orderDate) = $month
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                      else {

                          echo '<h2 class="text-center">Transactions during ';
                          echo 'August to September';
                          echo ' ';
                          echo $year;
                          echo '</h2><br>';

                          while ($row = mysqli_fetch_array($result2)) {
                              $sum = $row['sum'];
                          }

                          while ($row = mysqli_fetch_array($result)) {

                              $id = $row['JOID'];
                              $name = $row['cName'];
                              $totalPrice = $row['totalPrice'];
                              $date = $row['orderDate'];

                              echo '<tr>';

                              echo '<td class="text-center">';
                              echo '<a href="detailsJO.php?id='.$id.'">';
                              echo $id;
                              echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $name;
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $date;
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
                                  // echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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
                    }
                    elseif($month == 16){
                      $year = $_POST['year'];
                      $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                    customer.company AS cName,
                                                    joborder.orderDate AS orderDate,
                                                    joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE MONTH(joborder.orderDate) = 10
                                                    OR MONTH(joborder.orderDate) = 11
                                                    OR MONTH(joborder.orderDate) = 12
                                                    AND YEAR(joborder.orderDate) = $year
                                                    AND joborder.type = 'Made to Order'
                                                    AND joborder.status = 'Finished'
                                                    ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE MONTH(joborder.orderDate) = $month
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                      else {

                          echo '<h2 class="text-center">Transactions during ';
                          echo 'October to December';
                          echo ' ';
                          echo $year;
                          echo '</h2><br>';

                          while ($row = mysqli_fetch_array($result2)) {
                              $sum = $row['sum'];
                          }

                          while ($row = mysqli_fetch_array($result)) {

                              $id = $row['JOID'];
                              $name = $row['cName'];
                              $totalPrice = $row['totalPrice'];
                              $date = $row['orderDate'];

                              echo '<tr>';

                              echo '<td class="text-center">';
                              echo '<a href="detailsJO.php?id='.$id.'">';
                              echo $id;
                              echo '</a>';
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $name;
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo number_format($totalPrice, 2);
                              echo '</td>';

                              echo '<td class="text-center">';
                              echo $date;
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
                                  // echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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
                    }
                  elseif($month==17){
                    
                    $year = $_POST['year'];
                    $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                  customer.company AS cName,
                                                  joborder.orderDate AS orderDate,
                                                  joborder.totalPrice AS totalPrice
                                                  FROM joborder
                                                  JOIN customer on joborder.customerID=customer.customerID
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'
                                                  AND joborder.status = 'Finished'
                                                  ORDER BY jobOrder.dueDate ASC");
                $count=mysqli_num_rows($result);

                $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                FROM joborder
                                                WHERE YEAR(joborder.orderDate) = $year
                                                AND joborder.type = 'Made to Order'");

                  if ($count == "0"){
                      echo '<h2 class="text-center">No transactions within the specified range</h2>';
                  }
                    else {

                        echo '<h2 class="text-center">Transactions during ';
                        echo $year;
                        echo '</h2><br>';

                        while ($row = mysqli_fetch_array($result2)) {
                            $sum = $row['sum'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['JOID'];
                            $name = $row['cName'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];

                            echo '<tr>';

                            echo '<td class="text-center">';
                            echo '<a href="detailsJO.php?id='.$id.'">';
                            echo $id;
                            echo '</a>';
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo number_format($totalPrice, 2);
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
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
                                // echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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
                  }
                  else{
                    $month = $_POST['month'];
                  $monthName = date('F', mktime(0, 0, 0, $month, 10));
                  $year = $_POST['year'];
                  $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                  customer.company AS cName,
                                                  joborder.orderDate AS orderDate,
                                                  joborder.totalPrice AS totalPrice
                                                  FROM joborder
                                                  JOIN customer on joborder.customerID=customer.customerID
                                                  WHERE MONTH(joborder.orderDate) = $month
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'
                                                  AND joborder.status = 'Finished'
                                                  ORDER BY jobOrder.dueDate ASC");
                  $count=mysqli_num_rows($result);

                  $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                  FROM joborder
                                                  WHERE MONTH(joborder.orderDate) = $month
                                                  AND YEAR(joborder.orderDate) = $year
                                                  AND joborder.type = 'Made to Order'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Transactions during ';
                        echo $monthName;
                        echo ' ';
                        echo $year;
                        echo '</h2><br>';

                        while ($row = mysqli_fetch_array($result2)) {
                            $sum = $row['sum'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['JOID'];
                            $name = $row['cName'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];

                            echo '<tr>';

                            echo '<td class="text-center">';
                            echo '<a href="detailsJO.php?id='.$id.'">';
                            echo $id;
                            echo '</a>';
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo number_format($totalPrice, 2);
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
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
                                // echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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
                  }
                }

                else{
                    $dataArr = array();
                    $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                        customer.company AS cName,
                                                        joborder.orderDate AS orderDate,
                                                        joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE joborder.type = 'Made to Stock'");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                    FROM joborder
                                                    WHERE joborder.type = 'Made to Stock'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">There are no transactions yet</h2>';
                    }
                    else {

                        while ($row = mysqli_fetch_array($result2)) {
                            $sum = $row['sum'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['JOID'];
                            $name = $row['cName'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];

                            echo '<tr>';

                            echo '<td class="text-center">';
                            echo '<a href="detailsJO.php?id='.$id.'">';
                            echo $id;
                            echo '</a>';
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-right">';
                            echo number_format($totalPrice, 2);
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
                            echo '</td>';

                            echo '</tr>';

                            $dataArr[] = $id;

                        }
                        unset($_SESSION['reportMTS']);
                        $_SESSION['reportMTS'] = $dataArr;

                        echo '</tbody>';
                        echo '</table>';

                        echo '<div class="container">';
                          echo '<div class="row">';
                            echo "<div class='col-lg-12'>";
                              echo "<div class=text-center>";
                                echo "<a href='printMTS.php' class='btn btn-primary'>Continue for print</a>";
                              echo "</div>";
                            echo "</div>";
                            // echo '<div class="col-lg-6">';
                            //   echo "<div class='text-right'>";
                            //     echo '<h4 class="text-right">Total Revenue: ';
                            //       echo number_format($sum, 2);
                            //     echo '</h4>';
                            //   echo "</div>";
                            // echo '</div>';
                          echo '</div>';
                        echo '</div>';

                    }
                }


                ?>


        </div>
    </div>
</div>
