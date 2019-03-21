<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }


  function fill_unit_select_box($conn){
    $output = '';
    $query = "SELECT * FROM Customer WHERE customerID != '1' ORDER BY company ASC";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["customerID"].'">'.$row["company"].'</option>';
    }

    return $output;
  }

  //main submit conditional
  if (isset($_POST['proceed'])) {
    $prod = $_POST['prod'];
    $quantity = $_POST['quantity'];

    $customer = $_POST['customer'];
    $deadline = $_POST['deadline'];
    $date = date("Y-m-d");
    $user = $_SESSION['userid'];
    $type = "Made to Order";
    $status = "Pending for approval";
    $subtotal = 0;
    $total = 0;


    // prod and quantity arrays



    $query = "INSERT INTO JobOrder(customerID,orderDate,dueDate,totalPrice,type,status,createdBy)
              VALUES('$customer','$date','$deadline','$total','$type','$status','$user')";

      $sql = mysqli_query($conn,$query);

    $query = "SELECT * FROM JobOrder ORDER BY orderID DESC LIMIT 1";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $joid = $row['orderID'];

    foreach ($prod as $key => $value) {
      $qty = $quantity[$key];

      $query = "SELECT * FROM Product WHERE productID = '$value'";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

        $subtotal = $row['productPrice'] * $quantity[$key];

        $total+=$subtotal;

      $query1 = "INSERT INTO Receipt(orderID,productID,quantity,subTotal)
                VALUES('$joid','$value','$qty','$subtotal')";

        $sql = mysqli_query($conn,$query1);
    }

    $query = "UPDATE JobOrder SET totalPrice = $total WHERE orderID = $joid";

      if (mysqli_query($conn,$query)) {
        echo "<script>
          alert('Job Order Posted!');
           window.location.replace('viewJobOrders.php');
              </script>";
      }else {
        echo "<script>alert('Failed!');</script>";
      }
  }
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
    <form class="" action="" method="post">

      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  <div class="text-left">
                    <div class="row">
                      <div class="col">
                        Job Order
                      </div>
                      <div class="col-sm-2">

                      </div>
                      <div class="col-sm-2">
                        <a href="sampleJO.php" class="btn btn-primary" style="color:white">go back</a>
                      </div>
                    </div>

                  </div>
              </h1>
              <!-- <hr class="style1"> -->
          </div>
      </div>

      <div class="row">
          <div class="col-lg-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Time</th>
                    </tr>
                </thead>

            <?php
              $dayz = 0;
              $checker = 0;
              $daysArr = array();

              $prod = $_POST['product'];
              $quantity = $_POST['quantity'];

              $sum = 0;
              foreach ($prod as $key => $value) {
                $qty = $quantity[$key];
                $query = "SELECT Product.name FROM Product WHERE productID = '$value'";

                  $sql = mysqli_query($conn,$query);

                  $row = mysqli_fetch_array($sql);

                $query1 = "SELECT SUM(timeNeed) FROM ProductProcess WHERE productID = '$value'";

                  $sql1 = mysqli_query($conn,$query1);

                  $row1 = mysqli_fetch_array($sql1);

                $time = $row1[0]*$quantity[$key];
                $sum+=$time;
                $qty = $quantity[$key];

                if (get_need_inventory3($conn,$value,$qty)) {
                  $checker++;
                }

             ?>


                <tbody>
                    <tr>
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $quantity[$key]; ?></td>
                      <td><?php echo seconds_datetime($time); ?></td>
                    </tr>
                </tbody>
                <input type="hidden" name="prod[]" value="<?php echo $value; ?>">
                <input type="hidden" name="quantity[]" value="<?php echo $quantity[$key]; ?>">
          <?php } ?>
            </table>




            <br>
            <p><b>Total Production Time:</b> <?php echo seconds_datetime($sum); ?></p>
            <hr class="style1">

            <?php

            if ($checker>0) {
              echo "<h5>Lacking Ingredients on the following:</h5>";
              foreach ($prod as $key => $value) {
                $qty = $quantity[$key];

                if (get_need_inventory3($conn,$value,$qty)) {
                  echo "<div class = 'container'>";
                  $inv = get_need_inventory3($conn,$value,$qty);
                    echo "<b>" . get_prodname($conn,$inv[0]['productid']) . "</b><br />";
                    foreach ($inv as $key => $value) {
                      $daysArr[] = get_suppdur($conn,$inv[$key]['supid']);
                      echo "<div style='text-indent: 20px'><b>Need: " . $inv[$key]['ingname'] . "</b> - " . number_format($inv[$key]['lacking']) ." " .$inv[$key]['uom']. "</div>";
                      echo "<div style='text-indent: 30px'>Supplier: ". get_suppname($conn,$inv[$key]['supid']) . " (Estimated Delivery - " . get_suppdur($conn,$inv[$key]['supid']) . " Days)</div>";
                    }
                  echo "</div>";
                }
              }
            }
            ?>


            <hr class="style1">

            <?php

            $query = "SELECT SUM(timeEstimate) FROM ProductionProcess";

            $sql = mysqli_query($conn,$query);

            $row = mysqli_fetch_array($sql);

            $time+=$row[0];

            $dayz+=5;

            if (!empty($daysArr)) {
              $dayz+=max($daysArr);
            }


            $date = strtotime($dayz." days ".$time." seconds");

             ?>

            <div class="row">
              <div class="col">
                  <p><b>Packaging & Delivery Time: 5 Days </b></p>
              </div>
              <div class="col">
                  <p><b class="pull-right">Estimated delivery Date: <?php echo date("M/d/Y",$date); ?></b></p>
              </div>
            </div>
            <hr class="style1">

          </div>
      </div><br>

        <div class="form-row">
          <div class="form-group col-sm-6">
            <label for="customer">Customer:</label>
            <select id="customer" class="form-control" name="customer">
              <option value="" disabled>Select Customer</option><?php echo fill_unit_select_box($conn); ?>
            </select>

            <small class="form-text text-muted">Not in the list of Customers? <a href="addCustomerToOrder.php?prod=''">Click here</a></small>
          </div>
          <div class="form-group col-sm-4">
            <label for="date">Deadline: </label>
            <input id="date" class="form-control" type="date" min="<?php echo date("Y-m-d",$date); ?>" required name="deadline" value="<?php echo date("Y-m-d",$date); ?>">
          </div>
        </div>
        <br>

          <div class="col">
            <input type="submit" class="btn btn-primary pull-right" name="proceed" value="Proceed to order">
          </div>



                  <br><br>

      </form>
  </div>
</div>
<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
