<?php
/**
* Created by PhpStorm.
* User: jmcervantes02
* Date: 17/11/2018
* Time: 4:43 PM
*/?>
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
                Made-To-Order Job Order Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" name="startDate">
                <input type="date" name="endDate"><br>
                <input type="submit" name="search">
            </form>
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
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                        customer.name AS cName,
                                                        joborder.orderDate AS orderDate,
                                                        joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE joborder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND joborder.type = 'Made to Order'");
                    $count=mysqli_num_rows($result);

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Transactions between ';
                        echo $startDate;
                        echo ' and ';
                        echo $endDate;
                        echo '</h2><br>';

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['JOID'];
                            $name = $row['cName'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];

                            echo '<tr>';
                            echo '<td class="text-center">';
                            echo $id;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $totalPrice;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
                            echo '</td>';

                        }
                    }
                }

                else{
                    $result = mysqli_query($conn, "SELECT joborder.orderID AS JOID,
                                                        customer.name AS cName,
                                                        joborder.orderDate AS orderDate,
                                                        joborder.totalPrice AS totalPrice
                                                    FROM joborder
                                                    JOIN customer on joborder.customerID=customer.customerID
                                                    WHERE joborder.type = 'Made to Order'");
                    $count=mysqli_num_rows($result);

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['JOID'];
                            $name = $row['cName'];
                            $totalPrice = $row['totalPrice'];
                            $date = $row['orderDate'];

                            echo '<tr>';
                            echo '<td class="text-center">';
                            echo $id;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $totalPrice;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
                            echo '</td>';

                        }
                    }
                }

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>