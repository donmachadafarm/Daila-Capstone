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

  // remove job order conditional submit button
  if(isset($_POST['remove'])){
    $id = $_POST['jo_id'];

    $query = "UPDATE `JobOrder` SET `status` = 'removed' WHERE `orderID` = $id";

    if(mysqli_query($conn,$query)){
      echo "<script>alert('Removed Job Order from list!')</script>";
    }

  }

  if (isset($_POST['out'])) {
    $orderid = $_POST['orderid'];
    $date = date('Y-m-d');
    $payment = $_POST['payment'];
    $or = $_POST['or'];

    $query = "INSERT INTO Sales (orderID,officialReceipt,saleDate,totalPrice,payment) VALUES ($orderid,$or,'$date',0,$payment)";

      mysqli_query($conn,$query);

    $query = "SELECT * FROM Sales ORDER BY salesID DESC LIMIT 1";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

    $salesid = $row['salesID'];

    $query = "SELECT * FROM Receipt  WHERE orderID = $orderid";

    $sql = mysqli_query($conn,$query);

    $arrprod = array();
    $arrqty = array();
    while ($row = mysqli_fetch_array($sql)) {
      $prod = $row['productID'];
      $qty = $row['quantity'];
      array_push($arrprod,$prod);
      array_push($arrqty,$qty);
    }

    $count = count($arrprod);
    $total = 0;

    for ($i=0; $i < $count; $i++) {
      $sql = mysqli_query($conn,"SELECT * FROM Product WHERE productID = $arrprod[$i]");

      $row = mysqli_fetch_array($sql);

      $subtotal = $arrqty[$i] * $row['productPrice'];

      $query1 = "INSERT INTO ProductSales(productID,salesID,quantity,subTotal) VALUES('{$arrprod[$i]}','{$salesid}','{$arrqty[$i]}','{$subtotal}')";

        $sql = mysqli_query($conn,$query1);

        $total += $subtotal;

      $query2 = "UPDATE Product SET quantity = quantity - '{$arrqty[$i]}' WHERE productID = '{$arrprod[$i]}'";

        mysqli_query($conn,$query2);


      }
      $query3 = "UPDATE Sales SET totalPrice = $total WHERE salesID = $salesid";

        mysqli_query($conn,$query3);

      $query4 = "UPDATE JobOrder SET status = 'Finished' WHERE orderID = $orderid";

        mysqli_query($conn,$query4);
    }




 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Made to Order - Invoice out
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Job Order ID</th>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if($result = mysqli_query($conn,'SELECT JobOrder.orderID AS ID,
                                                        JobOrder.dueDate AS duedate,
                                                        JobOrder.status AS status
                                                FROM JobOrder
                                                WHERE JobOrder.status = "For Out" AND JobOrder.type = "Made to Order"')){


                    while($row = mysqli_fetch_array($result)){
                        $id = $row['ID'];
                        $status = $row['status'];
                        $duedate = $row['duedate'];

                        echo '<tr>';
                          echo '<td class="text-center">';
                            echo '<a href="viewIndivJO.php?id='.$id.'"><button type="button"">Job Order#'.$id.'-View items</a>  ';
                          echo '</td>';

                          echo '<td class="text-center">';
                            echo $duedate;
                          echo'</td>';

                          echo '<td class="text-center">';
                              echo '<a href="#out'.$id.'" data-target="#out'.$id.'" data-toggle="modal">
                                <button type="button" class="btn btn-secondary btn-sm">
                                  Out
                                </button></a>  ';
                          echo '</td>';

                        echo '</tr>';

                          ?>

                          <!-- modal -->

              <div id="out<?php echo $id; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                    <form method="post">
                          <div class="modal-content">

                              <div class="modal-header">
                                  <h4>Notice</h4>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <div class="modal-body">
                                <input type="hidden" name="orderid" value="<?php echo $id; ?>">
                                <h3>Create invoice for Order?</h3>
                                  <br>
                                  <h6>Note: This action will make an invoice for the order specified!</h6><br>

                                    <div class="row">
                                      <div class="col text-center">
                                        <b>Name</b>
                                      </div>
                                      <div class="col text-center">
                                        <b>Quantity</b>
                                      </div>
                                      <div class="col text-center">
                                        <b>Subtotal</b>
                                      </div>
                                    </div>
                                      <?php
                                      $query1 = "SELECT Product.name,Receipt.quantity,Receipt.subTotal
                                                  FROM Receipt
                                                  JOIN Product ON Product.productID = Receipt.productID
                                                  WHERE Receipt.orderID = $id";

                                      $sqld = mysqli_query($conn,$query1);

                                      $total = 0;
                                      while ($row = mysqli_fetch_array($sqld)) {
                                        echo '<div class="row">';
                                          echo '<div class="col text-center">';
                                              echo $row[0];
                                          echo '</div>';

                                          echo '<div class="col text-center">';
                                              echo $row[1];
                                          echo '</div>';

                                          echo '<div class="col text-center">';
                                              echo number_format($row[2],2);
                                          echo '</div>';
                                        echo '</div>';

                                        $total += $row[2];
                                      }

                                      $query = "SELECT * FROM Sales WHERE orderID = '$id'";

                                      $sql = mysqli_query($conn,$query);

                                      $row = mysqli_fetch_array($sql);

                                      $remaining = $row['totalPrice'] - $row['payment'];
                                      $or = $row['officialReceipt'];
                                      $date = $row['saleDate'];
                                       ?>

                                                                             <hr class="style1">
                                    <?php echo "<br><p class='pull-center'>Total: " . number_format($total,2). "</p>"; ?>
                                      <p>
                                        Remaining Balance: <?php echo $remaining; ?><br />
                                        Last Paid: <?php echo $date; ?><br />
                                        Official Receipt: <?php echo $or; ?>
                                      </p>

                                      <input type="number" class="form-control" placeholder="Official Receipt" name="or" value="" required><br>
                                      <input type="number" class="form-control" placeholder="<?php echo $remaining; ?>" name="payment" value="<?php echo $remaining; ?>" readonly>
                                </div>
                                  <div class="modal-footer">

                                      <button type="submit" name="out" class="btn btn-primary">Continue</button>
                                      <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                  </div>
                              </div>
                      </form>
                      </div>
                  </div>
              </div>


                <?php
                      }
                    }
                    ?>

                  <br><br>
                </tbody></table>



        </div>
    </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
