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
                Per Item Purchase Order Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" id="txtDateMax" name="startDate">
                <input type="date" id="endPicker" name="endDate"><br>
                <input type="submit" name="search">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Purchase Order ID</th>
                    <th>Materials Ordered</th>
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
                    $result = mysqli_query($conn, "SELECT purchaseorder.purchaseOrderID AS POID,
                                                        purchaseorder.orderDate AS orderDate,
                                                        purchaseorder.deadline AS dueDate,
                                                        purchaseorder.totalPrice AS total,
                                                        purchaseorder.status
                                                    FROM purchaseorder
                                                    WHERE purchaseorder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND purchaseorder.status!='removed'
                                                    ORDER BY purchaseorder.purchaseOrderID DESC");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(purchaseorder.totalPrice) AS sum
                                                    FROM purchaseorder
                                                    WHERE purchaseorder.orderDate BETWEEN '$startDate' AND '$endDate'
                                                    AND purchaseorder.status!='removed'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Transactions between ';
                        echo $startDate;
                        echo ' and ';
                        echo $endDate;
                        echo '</h2>';

                        while ($row = mysqli_fetch_array($result2)){
                            $sum = $row['sum'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['POID'];
                            $date = $row['orderDate'];
                            $ddate = $row['dueDate'];
                            $total = $row['total'];
                            $stat = $row['status'];

                            echo '<tr>';
                            echo '<td class="text-center">';
                            echo $id;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo '<a href="detailsJO.php?id='.$id.'">';
                            echo 'View Items</a>';
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $ddate;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $total;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $stat;
                            echo '</td>';
                            echo '</tr>';

                        }
                    }
                }
                else{
                    $result = mysqli_query($conn, "SELECT purchaseorder.purchaseOrderID AS POID,
                                                        purchaseorder.orderDate AS orderDate,
                                                        purchaseorder.deadline AS dueDate,
                                                        purchaseorder.totalPrice AS total,
                                                        purchaseorder.status 
                                                    FROM purchaseorder
                                                    WHERE purchaseorder.status!='removed'
                                                    ORDER BY purchaseorder.purchaseOrderID DESC");

                    $result2 = mysqli_query($conn, "SELECT SUM(purchaseorder.totalPrice) AS sum
                                                    FROM purchaseorder
                                                    WHERE purchaseorder.status!='removed'");

                    while ($row = mysqli_fetch_array($result2)){
                        $sum = $row['sum'];
                    }

                    while ($row = mysqli_fetch_array($result)) {

                        $id = $row['POID'];
                        $date = $row['orderDate'];
                        $ddate = $row['dueDate'];
                        $total = $row['total'];
                        $stat = $row['status'];

                        echo '<tr>';
                        echo '<td class="text-center">';
                        echo $id;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo '<a href="detailsPO.php?id='.$id.'">';
                        echo 'View Items</a>';
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $date;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $ddate;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $total;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $stat;
                        echo '</td>';
                        echo '</tr>';

                    }
                }
                ?>
                </tbody>
            </table>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="text-right">Total Expense: <?php echo $sum ?></h4>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    document.getElementById('txtDateMax').onchange = function () {
        document.getElementById('endPicker').setAttribute('min',  this.value);
    };
</script>