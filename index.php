<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->
<style>
.scroll {
    max-height: 550px;
    overflow-y: auto;
}
</style>
<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }
//   if($_SESSION['userType']=101){  //OPERATIONS ?>
<br><br><br>

<?php
// }elseif($_SESSION['userType']=102){ //WAREHOUSE ?>



<?php
// }elseif($_SESSION['userType']=103){ //PLANT ?>


<?php
// }elseif($_SESSION['userType']=100){ //KAICHO ?>


<?php
// }else{ //ADMIN ?>

<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header">
                  Homepage!
              </h1>
          </div>
      </div>

      <!-- <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body scroll">
            <?php
              $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
              product.quantity AS quantity,
              productType.name AS producttypename,
              product.productPrice,
              product.productID AS ID
              FROM product
              JOIN productType ON product.productTypeID=productType.productTypeID
              WHERE product.custom <> 1
              GROUP BY product.name
              ");
              $count = 0;
              while ($row = mysqli_fetch_array($allInventory)){
                $id = $row['ID'];
                $prodName = $row['productname'];
                $prodType = $row['producttypename'];
                $quantity = $row['quantity'];
                $price = $row['productPrice'];
                $restockingValue = 100;
                $maxLeadTime = get_maxlead($conn, $id);
                $averageSales = get_total_average($conn, $id);
                $reorderPoint = 100+($averageSales*$maxLeadTime);
                $needed = $reorderPoint-$quantity;

                if ($reorderPoint>$quantity){
                    $count++;
                }

            }
            ?>
              <h5 class="card-title">Products that need restocking<span class="badge badge-light pull-right"><?php echo $count ?></span></h5>
              <p class="card-text">Using the default Total Running Average Forecasting Algorithm.</p>
              <!-- <a href="viewInventory.php" class="btn btn-warning">Restock</a> -->
              <?php
                if($count>0){
              ?>
              <button id="myButton" class="btn btn-warning" >Restock<span class="badge badge-light pull-right"><?php echo $count ?></span></button>
              <?php
                }
                else{
              ?>
              <button id="myButton" class="btn btn-primary" >Restock<span class="badge badge-light pull-right"><?php echo $count ?></span></button>
              <?php
                }
              ?>
              <?php
              mysqli_data_seek($allInventory, 0);
              ?>
              <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                  <?php

                    while ($row = mysqli_fetch_array($allInventory)){
                      $id = $row['ID'];
                      $prodName = $row['productname'];
                      $prodType = $row['producttypename'];
                      $quantity = $row['quantity'];
                      $price = $row['productPrice'];
                    echo '<tr>';
                    echo '<td><a href="viewIndivProduct.php?id='.$id.'">';
                    echo $prodName;
                    echo '</a></td>';
                    echo '<td>';
                    echo $quantity;
                    echo '</td>';
                    echo '<td>';
                    echo $prodType;
                    echo'</td>';
                    echo '<td>';
                    echo $price;
                    echo'</td>';
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body scroll">
            <?php
              $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
              Ingredient.name AS name,
              Ingredient.quantity AS quantity,
              RawMaterial.unitOfMeasurement AS uom
              FROM Ingredient
              JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
              JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
              ORDER BY ingredient.ingredientID');
              $count2=0;
              while($row2 = mysqli_fetch_array($result2)){
                $id2 = $row2['id'];
                $name2 = $row2['name'];
                $qty2 = $row2['quantity'];
                $uom2 = $row2['uom'];
                $products2 = get_ingredients($conn, $id2);
                $total2 = 0;
                $unit2;
                $inNeeded2 = 0;


                // set collapse box for notifs

                foreach($products2 as $prod2){
                  $prodID2 = $prod2['pid'];
                  $ave2 = ceil(get_total_average($conn, $prodID2));

                  $mlt2 = get_maxlead($conn, $prodID2);

                  $reorderpoint2 = ($ave2*$mlt2)+100;

                  $recipeQuantity2 = $prod2['quantity'];

                  $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                  $total2 += $inNeeded2;
                }

                if ($total2>$qty2){
                  $count2 += 1;
                }

              }
            ?>
              <h5 class="card-title">Ingredients that need restocking<span class="badge badge-light pull-right"><?php echo $count2 ?></span></h5>
              <p class="card-text">Using the default Total Running Average Forecasting Algorithm.</p>
              <!-- <button id="myButton2" class="btn btn-primary" >Restock<span class="badge badge-light pull-right"><?php echo $count2 ?></span></button> -->
              <?php
                if($count2>0){
              ?>
              <button id="myButton2" class="btn btn-warning" >Restock<span class="badge badge-light pull-right"><?php echo $count2 ?></span></button>
              <?php
                }
                else{
              ?>
              <button id="myButton2" class="btn btn-primary" >Restock<span class="badge badge-light pull-right"><?php echo $count2 ?></span></button>
              <?php
                }
                mysqli_data_seek($result2, 0);
              ?>
              <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Ingredient Name</th>
                    <th>Quantity</th>
                    <th>Unit of Measurement</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                    // Ingredient.name AS name,
                    // Ingredient.quantity AS quantity,
                    // RawMaterial.unitOfMeasurement AS uom
                    // FROM Ingredient
                    // JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                    // JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                    // ORDER BY ingredient.ingredientID');
                    $count2=0;
                    while($row2 = mysqli_fetch_array($result2)){
                      $name2 = $row2['name'];
                      $qty2 = $row2['quantity'];
                      $uom2 = $row2['uom'];

                      echo '<tr>';
                      echo '<td>';
                      echo $name2;
                      echo '</td>';
                      echo '<td>';
                      echo abs(ceil($qty2));
                      echo '</td>';
                      echo '<td>';
                      echo $uom2;
                      echo '</td>';
                      echo '</tr>';

                    }
                  ?>
            </div>
          </div>
        </div>
      </div> -->
  </div>
</div>

<!-- <?php  ?> -->

<!-- end of content -->
<script type="text/javascript">
    document.getElementById("myButton").onclick = function () {
        location.href = "viewInventory.php";
    };

    document.getElementById("myButton2").onclick = function () {
        location.href = "viewIngredients.php";
    };
</script>

<?php include "includes/sections/footer.php"; ?>
