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
  <div id="page-wrapper">

      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  <div class="text-center">
                    Mock Job Order<a href="sampleJO.php" class="btn btn-primary btn-sm pull-right" style="color:white">go back</a>
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
                      $dayz+=get_suppdur($conn,$inv[$key]['supid']);
                      echo "<div style='text-indent: 20px'>Need: " . $inv[$key]['ingname'] . " - " . number_format($inv[$key]['needqty']) . "Pcs</div>";
                      echo "<div style='text-indent: 30px'>Supplier: ". get_suppname($conn,$inv[$key]['supid']) . " (Estimated Delivery - " . get_suppdur($conn,$inv[$key]['supid']) . " Days)</div>";
                    }
                  echo "</div>";
                }
              }
            }
            ?>


            <hr class="style1">

            <div class="container">
              <p><b>Packaging & Delivery Time: 5 Days </b></p>
            </div>

            <hr class="style1">

            <div class="pull-right">

              <?php

              $query = "SELECT SUM(timeEstimate) FROM ProductionProcess";

              $sql = mysqli_query($conn,$query);

              $row = mysqli_fetch_array($sql);

              $time+=$row[0];

              $dayz+=5;

              $date = strtotime($dayz." days ".$time." seconds");

               ?>

              <p><b>Delivery Date: <?php echo date("M/d/Y",$date); ?></b></p>

            </div>

          </div>
      </div>

  </div>
</div>
<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
