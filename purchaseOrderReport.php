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
                    <th>Purchase Order ID</th>
                    <th>Material Name</th>
                    <th>Total Ordered</th>
                    <th>Unit</th>
                    <th>Total Price</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $result = mysqli_query($conn, "SELECT purchaseorder.purchaseOrderID AS POID,
                                                        rawmaterial.name AS RMname,
                                                        poitem.quantity AS amount,
                                                        poitem.unitOfMeasurement AS UoM,
                                                        poitem.subTotal AS totalPrice,
                                                        supplier.name AS supplierName,
                                                        purchaseorder.orderDate AS orderDate,
                                                        poitem.status 
                                                    FROM purchaseorder
                                                    JOIN poitem on purchaseorder.purchaseOrderID=poitem.purchaseOrderID
                                                    JOIN supplier on purchaseorder.supplierID=supplier.supplierID
                                                    JOIN rawmaterial on poitem.rawMaterialID=rawmaterial.rawMaterialID
                                                    WHERE purchaseorder.orderDate BETWEEN '$startDate' AND '$endDate' 
                                                    ORDER BY purchaseorder.purchaseOrderID DESC");
                    $count=mysqli_num_rows($result);

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Transactions between ';
                        echo $startDate;
                        echo ' and ';
                        echo $endDate;
                        echo '</h2>';

                        while ($row = mysqli_fetch_array($result)) {

                            $id = $row['POID'];
                            $name = $row['RMname'];
                            $totalOrder = $row['amount'];
                            $unit = $row['UoM'];
                            $totalPrice = $row['totalPrice'];
                            $supplier = $row['supplierName'];
                            $date = $row['orderDate'];
                            $stat = $row['status'];

                            echo '<tr>';
                            echo '<td class="text-center">';
                            echo $id;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $totalOrder;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $unit;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $totalPrice;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $supplier;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $date;
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
                                                        rawmaterial.name AS RMname,
                                                        poitem.quantity AS amount,
                                                        poitem.unitOfMeasurement AS UoM,
                                                        poitem.subTotal AS totalPrice,
                                                        supplier.name AS supplierName,
                                                        purchaseorder.orderDate AS orderDate,
                                                        poitem.status 
                                                    FROM purchaseorder
                                                    JOIN poitem on purchaseorder.purchaseOrderID=poitem.purchaseOrderID
                                                    JOIN supplier on purchaseorder.supplierID=supplier.supplierID
                                                    JOIN rawmaterial on poitem.rawMaterialID=rawmaterial.rawMaterialID 
                                                    ORDER BY purchaseorder.purchaseOrderID DESC");

                    while ($row = mysqli_fetch_array($result)) {

                        $id = $row['POID'];
                        $name = $row['RMname'];
                        $totalOrder = $row['amount'];
                        $unit = $row['UoM'];
                        $totalPrice = $row['totalPrice'];
                        $supplier = $row['supplierName'];
                        $date = $row['orderDate'];
                        $stat = $row['status'];

                        echo '<tr>';
                        echo '<td class="text-center">';
                        echo $id;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $name;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $totalOrder;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $unit;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $totalPrice;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $supplier;
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo $date;
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
        </div>
    </div>
</div>