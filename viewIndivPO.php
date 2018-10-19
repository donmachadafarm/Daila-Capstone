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

  $query = "SELECT Supplier.name AS suppName,
                                       PurchaseOrder.purchaseOrderID AS id,
                                       PurchaseOrder.totalPrice AS price,
                                       PurchaseOrder.orderDate AS date,
                                       PurchaseOrder.status AS status
                                  FROM PurchaseOrder
                                  INNER JOIN Supplier ON PurchaseOrder.supplierID =Supplier.supplierID";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $name = $row['suppName'];
    $price = $row['price'];
    $status = $row['status'];
    $date = $row['date'];

 ?>
<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   Purchase Order to supplier: <?php echo $name; ?>
              </h1>
          </div>
      </div><a href="viewPurchaseOrders.php" class="btn btn-primary btn-sm float-right">go back</a>
      <div class="row">
          <div class="col-lg-10">
            <table class="table table-borderless" id="dataTables-example">
              <tr>
                <td>Total Cost for PO: <?php echo $price; ?></td>
                <td>Current status of PO: <?php echo $status; ?></td>
                <td>Purchase Order date posted: <?php echo $date; ?></td>
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
                <th>Sub Total</th>
              </tr>
            </thead>
            <tbody>

                <?php

                $query = "SELECT RawMaterial.name AS rmname,
                                 POItem.quantity AS qty,
                                 POItem.subTotal AS sub
                          FROM POItem
                          INNER JOIN RawMaterial ON POItem.rawMaterialID = RawMaterial.rawMaterialID
                          INNER JOIN PurchaseOrder ON POItem.purchaseOrderId = PurchaseOrder.purchaseOrderID
                          WHERE PurchaseOrder.purchaseOrderID = $id";

                $sql = mysqli_query($conn,$query);

                while ($row = mysqli_fetch_array($sql)) {
                    $name = $row['rmname'];
                    $qty = $row['qty'];
                    $sub = $row['sub'];

                    echo "<tr>";
                      echo "<td>";
                        echo $name;
                      echo "</td>";
                      echo "<td>";
                        echo $qty;
                      echo "</td>";
                      echo "<td>";
                        echo $sub;
                      echo "</td>";
                    echo "</tr>";
                }
                 ?>

            </tbody>
          </table>
        </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
