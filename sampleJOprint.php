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
              $prod = $_POST['product'];
              $quantity = $_POST['quantity'];

              $sum = 0;
              foreach ($prod as $key => $value) {
                $query = "SELECT Product.name FROM Product WHERE productID = '$value'";

                  $sql = mysqli_query($conn,$query);

                  $row = mysqli_fetch_array($sql);

                $query1 = "SELECT SUM(timeNeed) FROM ProductProcess WHERE productID = '$value'";

                  $sql1 = mysqli_query($conn,$query1);

                  $row1 = mysqli_fetch_array($sql1);

                $time = $row1[0]*$quantity[$key];
                $sum+=$time;
                $qty = $quantity[$key];

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
              $checker = 0;

              foreach ($prod as $key => $value) {
                $qty = $quantity[$key];

                $query1 = "SELECT Ingredient.ingredientID AS ingid,
                                  Ingredient.quantity AS CurrentInventoryQuantity,
                                  Recipe.quantity*$qty AS NeededIngredientQuantity
                            FROM `Recipe`
                            INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                            WHERE Recipe.productID = $value";

                  $sql1 = mysqli_query($conn,$query1);

                  $ingar = array();
                  $prid = array();
                  while ($rowed = mysqli_fetch_array($sql1)) {
                    $ingid = $rowed['ingid'];
                    $ingquant = $rowed['NeededIngredientQuantity'];
                    $currinvq = $rowed['CurrentInventoryQuantity'];

                    if ($currinvq < $ingquant) {
                      $prid[] = $value;
                      $ingar[] = $ingid;
                      $checker++;
                    }
                  }
                }
                  $p = array_unique($prid);
                ?>

                <?php if ($checker>0): ?>
                  <div class="container">
                    <h5>Products with lacking ingredients</h5>

                    <p>
                <?php
                  $dayz = 0;
                  foreach ($p as $key => $value) {

                    $sq = mysqli_query($conn,"SELECT * FROM Product WHERE productid = '$value'");
                    $r = mysqli_fetch_array($sq);
                  ?>
                  <h6>Product: <?php echo $r['name']; ?></h6>

                  <?php

                    foreach ($ingar as $k => $v) {

                      $sq1 = mysqli_query($conn,"SELECT Ingredient.name,
                                                        RMIngredient.rawMaterialID,
                                                        RawMaterial.rawMaterialID,
                                                        RawMaterial.supplierID,
                                                        Supplier.company,
                                                        MAX(Supplier.duration)
                                                 FROM RMIngredient
                                                 JOIN Ingredient ON Ingredient.ingredientID = RMIngredient.ingredientID
                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                 JOIN Supplier ON Supplier.supplierID = RawMaterial.rawMaterialID
                                                 WHERE Ingredient.ingredientID = $v");

                      while ($r1 = mysqli_fetch_array($sq1)) {
                        $dayz+=$r1[5];
                        echo $r1[4]." (Days to deliver: ".$r1[5].") - ";
                        echo $r1['name']."<br />";
                      } ?>
                  </p>


                <?php }
                    } endif; ?>
                  </div>

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
