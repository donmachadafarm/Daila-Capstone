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

<?php
$id = $_GET['id'];

$query = "SELECT Supplier.company AS suppName,
                                       PurchaseOrder.purchaseOrderID AS id,
                                       PurchaseOrder.totalPrice AS pricez,
                                       PurchaseOrder.orderDate AS date,
                                       PurchaseOrder.deadline AS deadline,
                                       PurchaseOrder.status AS status
                                  FROM PurchaseOrder
                                  INNER JOIN Supplier ON PurchaseOrder.supplierID =Supplier.supplierID
                                  WHERE purchaseOrderID = $id
                                  ORDER BY id DESC LIMIT 1";

$sql = mysqli_query($conn,$query);

$row = mysqli_fetch_array($sql);

$name = $row['suppName'];
$pricez = $row['pricez'];
$deadline = $row['deadline'];
$status = $row['status'];
$date = $row['date'];


// update status PO and add all the requested raw mats into inventory (INVENTORY AND RAW MATERIALS UPDATE)
if (isset($_POST['update'])) {
    // query here
    $ids = $_POST['poitem_id'];
    $rmid = $_POST['rmid'];
    $poid = $_POST['poid'];

    $query = "SELECT * FROM POItem WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $qty = $row['quantity'];

    $query = "SELECT * FROM RMIngredient WHERE rawMaterialID = $rmid";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $iding = $row['ingredientID'];

    $query = "UPDATE ingredient SET quantity = quantity + $qty WHERE ingredientID = $iding";

    if(mysqli_query($conn,$query)){

        mysqli_query($conn,"UPDATE POItem SET status = 'Delivered' WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid");

        echo "<meta http-equiv='refresh' content='0'>";

    }

    $sql = mysqli_query($conn,"SELECT * FROM POItem WHERE purchaseOrderID = $poid AND status = 'Not Delivered'");

    $count = mysqli_num_rows($sql);

    if($count == 0){
        mysqli_query($conn,"UPDATE PurchaseOrder SET status = 'Completed!' WHERE purchaseOrderID = $poid");
    }


}

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Purchase Order requested to <?php echo $name; ?>
            </h1>
        </div>
    </div>
    <a href="purchaseOrderReport.php" class="btn btn-primary btn-sm float-right">go back</a>
    <!-- <a href="" class="btn btn-danger btn-sm float-right">Remove</a> -->
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless" id="dataTables-example">
                <tr>
                    <td>Total Cost for PO: <b><?php echo number_format($pricez, 2); ?></b></td>
                    <td>Current status of PO: <?php echo $status; ?></td>
                    <td>Purchase Order date posted: <?php echo $date; ?></td>
                    <td>Deadline for supplier: <?php echo $deadline; ?></td>
                </tr>
            </table>
        </div>
    </div><br><br><br>
    <div class="row">
        <div class="col-lg-12">
            <h3>List of Raw Materials Ordered</h3>
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Raw Material</th>
                    <th>Quantity</th>
                    <th>Unit of Measurement</th>
                    <th>Price per unit</th>
                    <th>Sub Total</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php

                $query = "SELECT RawMaterial.rawMaterialID AS rmid,
                                 RawMaterial.name AS rmname,
                                 RawMaterial.pricePerUnit AS ppu,
                                 POItem.purchaseOrderID AS poid,
                                 POItem.quantity AS qty,
                                 POItem.subTotal AS sub,
                                 POItem.status AS status,
                                 POItem.unitOfMeasurement AS uom
                          FROM POItem
                          INNER JOIN RawMaterial ON POItem.rawMaterialID = RawMaterial.rawMaterialID
                          INNER JOIN PurchaseOrder ON POItem.purchaseOrderId = PurchaseOrder.purchaseOrderID
                          WHERE PurchaseOrder.purchaseOrderID = $id";

                $sql = mysqli_query($conn,$query);

                while ($row = mysqli_fetch_array($sql)) {
                $poid = $row['poid'];
                $rmid = $row['rmid'];
                $name = $row['rmname'];
                $qty = $row['qty'];
                $sub = $row['sub'];
                $ppu = $row['ppu'];
                $status = $row['status'];
                $uom = $row['uom'];

                echo "<tr>";
                echo "<td>";
                echo $name;
                echo "</td>";

                echo "<td>";
                echo $qty;
                echo "</td>";

                echo "<td>";
                echo $uom;
                echo "</td>";

                echo "<td>";
                echo number_format($ppu, 2);
                echo "</td>";

                echo "<td>";
                echo number_format($sub, 2);
                echo "</td>";

                echo "<td>";
                echo $status;
                echo "</td>";

                echo "</tr>";
                ?>
        <?php
        }
        ?>

        </tbody>
        </table>
    </div>
</div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
