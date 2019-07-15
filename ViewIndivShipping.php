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

  // echo "<meta http-equiv='refresh' content='0'>";

    // update status PO and add all the requested raw mats into inventory (INVENTORY AND RAW MATERIALS UPDATE)
    if (isset($_POST['update'])) {
      // query here
      $joid = $_POST['joborderid'];
      $prodid = $_POST['productid'];
      $qty = $_POST['qty'];

      $query = "UPDATE Product SET quantity = quantity + $qty WHERE productID = '$prodid'";

        mysqli_query($conn,$query);

      $query = "UPDATE Shipping SET shippingQuantity = shippingQuantity - $qty, shippedQuantity = shippedQuantity + $qty WHERE productID = '$prodid' AND orderID = '$joid'";

        mysqli_query($conn,$query);

      $sql = mysqli_query($conn,"SELECT * FROM Shipping WHERE orderid = '$joid' AND productID = '$prodid'");

      $row = mysqli_fetch_array($sql);

      if ($row['shippingQuantity'] == 0) {
        mysqli_query($conn,"UPDATE Shipping SET status = 'Shipped' WHERE orderid = '$joid' AND productID = '$prodid'");
      }

      // checker if there are still shipping status in production
      $sql = mysqli_query($conn,"SELECT * FROM Production WHERE productID = $prodid AND orderID = $joid AND status = 'Shipping'");

      $count = mysqli_num_rows($sql);

      if($count == 0){
        mysqli_query($conn,"UPDATE JobOrder SET status = 'For Out' WHERE orderID = $joid");
      }


    }




 ?>

<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   Internal Shipping for Job Order # <?php echo $id; ?>
              </h1>
          </div>
      </div>
      <a href="viewInternalShipping.php" class="btn btn-primary btn-sm float-right" style="color:white">go back</a>
      <div class="row">
        <div class="col-lg-12">
          <h3>List of Products in Job Order</h3><br><hr class="style1"><br>
          <table class="table table-bordered table-hover" >
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Quantity Remaining</th>
                <th>Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>

                <?php

                $query = "SELECT Product.name AS name,
                                 Product.productID AS prodid,
                                 Shipping.shippingQuantity AS qty,
                                 Shipping.status AS status
                          FROM JobOrder
                          JOIN Shipping ON Shipping.orderID = JobOrder.orderID
                          JOIN Product ON Shipping.productID = Product.productID
                          WHERE Shipping.orderID = $id";

                $sql = mysqli_query($conn,$query);

                while ($row = mysqli_fetch_array($sql)) {
                    $name = $row['name'];
                    $qty = $row['qty'];
                    $status = $row['status'];
                    $prodid = $row['prodid'];

                    echo "<tr>";
                      echo "<td>";
                        echo $name;
                      echo "</td>";

                      echo "<td>";
                        echo $qty;
                      echo "</td>";

                      echo "<td>";
                        echo $status;
                      echo "</td>";

                      echo "<td class='text-center'>";
                      if ($status != 'Shipped') {
                        echo '<a href="#update'.$prodid.'" data-target="#update'.$prodid.'" data-toggle="modal"><button type="button" class="btn btn-success btn-sm">Receive</button></a>';
                      }else {
                        echo '<button type="button" class="btn btn-sm btn-secondary" disabled>Received</button>';
                      }
                      echo "</td>";

                    echo "</tr>";
                    ?>

                    <div id="update<?php echo $prodid; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="joborderid" value="<?php echo $id; ?>">
                                        <input type="hidden" name="productid" value="<?php echo $prodid; ?>">
                                        <div class="text-center">
                                          <p>
                                            <h5>How many arrived?</h5>
                                            <br>
                                            <div class="text-left">
                                              <label class="text-left"><b>Expecting:</b></label>
                                            </div>

                                            <input type="number" placeholder="<?php echo $qty; ?>" max="<?php echo $qty;?>" min="1" name="qty" class="form-control" value="">
                                          </p>
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
