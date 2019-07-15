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

    if (isset($_POST['update'])) {
        $rmid = $_POST['rmid'];
        $poid = $_POST['poid'];
        $good = $_POST['good'];
        $ornum = $_POST['delivery'];

        $query = "SELECT * FROM POItem WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid";

          $sql = mysqli_query($conn,$query);

          $row = mysqli_fetch_array($sql);

          $origqty = $row['quantity'];
          $shipqty = $row['quantityShipped'];

        $defective = $origqty - $good;

        if ($defective >= 0) {
          $query1 = "UPDATE POItem SET quantityShipped = quantityShipped + '$good', defective = defective + '$defective',deliveryReceipt = '$ornum' WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid";
        }else {
          $query1 = "UPDATE POItem SET quantityShipped = quantityShipped + '$good', deliveryReceipt = '$ornum' WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid";
        }

        mysqli_query($conn,$query1);

        $query = "SELECT * FROM RMIngredient WHERE rawMaterialID = $rmid";

          $sql = mysqli_query($conn,$query);

          $row = mysqli_fetch_array($sql);

        // ingredient id matched with rawmaterial
        $iding = $row['ingredientID'];

        $query = "UPDATE ingredient SET quantity = quantity + $good WHERE ingredientID = $iding";

          mysqli_query($conn,$query);

        $query = "SELECT * FROM POItem WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid";

          $sql = mysqli_query($conn,$query);

          $row = mysqli_fetch_array($sql);

          $origqty = $row['quantity'];
          $shipqty = $row['quantityShipped'];

        if ($origqty == $shipqty) {
          mysqli_query($conn,"UPDATE POItem SET status = 'Delivered',deliveryReceipt = '$ornum' WHERE rawMaterialID = $rmid AND purchaseOrderID = $poid");

          echo "<meta http-equiv='refresh' content='0'>";
        }

        // checks if whole PO is completed
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
      <a href="viewPurchaseOrders.php" class="btn btn-primary btn-sm float-right">go back</a>
      <!-- <a href="" class="btn btn-danger btn-sm float-right">Remove</a> -->
      <div class="row">
          <div class="col-lg-12">
            <table class="table table-borderless" id="dataTables-example">
              <tr>
                <td>Total Cost for PO: <b><?php echo $pricez; ?></b></td>
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
                <th>Shipped</th>
                <th>Unit of Measurement</th>
                <th>Price per unit</th>
                <th>Sub Total</th>
                <th>Status</th>
                <th class="text-center">Action</th>
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
                                 POItem.defective AS def,
                                 POItem.quantityShipped AS remaining,
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
                    $defe = $row['def'];
                    $ppu = $row['ppu'];
                    $status = $row['status'];
                    $uom = $row['uom'];
                    $rem = $row['remaining'];
                    $maxdefe = $qty - $defe;
                    $actual = $qty - $rem;


                    echo "<tr>";
                      echo "<td>";
                        echo $name;
                      echo "</td>";

                      echo "<td>";
                        echo $qty;
                      echo "</td>";

                      echo "<td>";
                        echo $rem;
                      echo "</td>";

                      echo "<td>";
                        echo $uom;
                      echo "</td>";

                      echo "<td class='text-right'>";
                        echo number_format($ppu,2);
                      echo "</td>";

                      echo "<td class='text-right'>";
                        echo number_format($sub,2);
                      echo "</td>";

                      echo "<td>";
                        echo $status;
                      echo "</td>";

                      echo "<td class='text-center'>";
                      if ($status != 'Delivered') {
                        echo '<a href="#update'.$rmid.'" data-target="#update'.$rmid.'" data-toggle="modal"><button type="button" class="btn btn-success btn-sm">Update Status</button></a>';
                      }else {
                        echo '<button type="button" class="btn btn-sm btn-secondary" disabled>Received</button>';
                      }
                      echo "</td>";

                    echo "</tr>";
                    ?>

                    <div id="update<?php echo $rmid; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="poitem_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="rmid" value="<?php echo $rmid; ?>">
                                        <input type="hidden" name="poid" value="<?php echo $poid; ?>">
                                        <div class="text-center">
                                          <p>
                                            <h6><b>Update Purchase Order Status?</h6></b>
                                            <br>
                                            <div class="form-group row">
                                              <label class="col-sm-4 col-form-label">Delivery Receipt: </label>
                                              <div class="col-sm-8">
                                                <input required type="number" name="delivery" value="" placeholder="" class="form-control" required><br>
                                              </div>
                                            </div>

                                            <div class="form-group row">
                                              <label class="col-sm-4 col-form-label">Good Condition: </label>
                                              <div class="col-sm-8">
                                                <input required type="number" name="good" placeholder="" class="form-control" min="0" max="<?php echo $actual; ?>"required>
                                              </div>
                                            </div>
                                          </p>
                                          <small>Note: This action will add the requested Good Raw Materials into the inventory!</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update" class="btn btn-primary">Continue</button>
                                            <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>
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
