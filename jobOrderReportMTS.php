<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}
?>

<!-- put all the contents here  -->

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                Made-To-Stock Job Order Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" id="txtDateMax" name="startDate">
                <input type="date" max="<?php echo date("Y-m-d"); ?>" name="endDate"><br>
                <input class="btn btn-success" type="submit" name="search">
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
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                    $dataArr = array();
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                        customer.company AS cName,
                                                        joborder.orderDate AS orderDate,
                                                        joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND joborder.type = 'Made to Stock'");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(joborder.totalPrice) AS sum
                                                    FROM joborder
                                                    WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND joborder.type = 'Made to Stock'");

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
                                echo "<a href='printMTS.php?start=$startDate&end=$endDate' class='btn btn-primary'>Print this report</a>";
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

<script>
    document.getElementById('txtDateMax').onchange = function () {
        document.getElementById('endPicker').setAttribute('min',  this.value);
    };
</script>
