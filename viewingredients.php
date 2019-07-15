<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }

  if (isset($_POST["example"])) {
    $example = $_POST["example"];
  }else{
      $example = 1;
  }

  $dateNow = date("Y-m-d");
  $yearAgo = date("Y-m-d", strtotime("-1 year"));
  $halfYearAgo = date("Y-m-d", strtotime("-6 months"));
  $threeMonthsAgo = date("Y-m-d", strtotime("-3 months"));
  $thisMonth = date('m');
  $thisMonthWord = date('F');
  $thisYear = date('Y');
  $lastYear = date('Y', strtotime('-1 year'));


?>

<!-- put all the contents here  -->


<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  View Ingredients
              </h1>
              <label for="chooseAlgo">Choose Forecasting Algorithm:</label>
            <form method="post">
              <div class="row">
                <div class="col-md-5">
                  <select class="form-control" id="chooseAlgo" name="example">
                      <option value="1" <?php if($example == '1') { ?> selected <?php } ?>>Total Running Average - All Sales Recorded</option>
                      <option value="2" <?php if($example == '2') { ?> selected <?php } ?>>Yearly Running Average - Past 1 Year</option>
                      <option value="3" <?php if($example == '3') { ?> selected <?php } ?>>6 Month Running Average - Past 6 Months</option>
                      <option value="4" <?php if($example == '4') { ?> selected <?php } ?>>3 Month Running Average - Past 3 Months</option>
                      <option value="5" <?php if($example == '5') { ?> selected <?php } ?>>Seasonality - All Sales from the Month of <?php echo $thisMonthWord?></option>
                      <option value="6" <?php if($example == '6') { ?> selected <?php } ?>>This Year's Average - All Sales from the Year of <?php echo $thisYear?></option>
                      <option value="7" <?php if($example == '7') { ?> selected <?php } ?>>Last Year's Average - All Sales from the Year of <?php echo $lastYear?></option>
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="submit" class="btn btn-primary" name="choose" id="submitButton" value="Submit">
                </div>
              </div>

            </form><br>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
                    <table class="table table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Ingredient Name</th>
                                <th>Quantity</th>
                                <th>Unit of Measurement</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php

                        if(!isset($_POST['choose'])){
                          //None selected - default to Total
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                          echo "<br>";
                            echo "<div class='collapse' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count=0;

                            $arrr = array();
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

                              $needed2 = ceil($total2-$qty2);

                              if ($needed2<0) {
                                $needed2=0;
                              }

                              if ($total2>$qty2){
                                array_push($arrr,$id2);
                                echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                                echo ceil($needed2);
                                echo " of ";
                                echo $name2;
                                echo " to maintain optimal quantity </div>";
                                $count += 1;
                              }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;

                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = get_total_average($conn, $prodID);

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ($ave*$mlt)+100;

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = $recipeQuantity*$reorderpoint;

                                $total += $inNeeded;
                              }
                              $needed = ceil($total-$qty);

                              if ($needed<0) {
                                $needed=0;
                              }
                                  echo '<tr>';
                                      if (in_array($id,$arrr)) {
                                        echo '<td class=table-danger>';
                                          echo $name;
                                        echo '</td>';
                                        echo '<td class=table-danger>';
                                          echo abs(ceil($qty));
                                        echo '</td>';
                                        echo '<td class=table-danger>';
                                          echo $uom;
                                        echo '</td>';
                                        echo '<td class="text-center table-danger">';
                                          echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.$needed.'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                        echo '</td>';
                                      }else {
                                        echo '<td>';
                                          echo $name;
                                        echo '</td>';
                                        echo '<td>';
                                          echo abs(ceil($qty));
                                        echo '</td>';
                                        echo '<td>';
                                          echo $uom;
                                        echo '</td>';
                                        echo '<td class="text-center">';
                                          echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.$needed.'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                        echo '</td>';
                                      }

                                  echo '</tr>';


                            }
                        }
                        elseif($example==1){
                          //Total
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count=0;
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

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;

                            if ($needed2<0) {
                              $needed2=0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_total_average($conn, $prodID));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed<0) {
                                $needed=0;
                              }
                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }
                        elseif($example==2){
                          //Past 1 Year
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count = 0;
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
                              $ave2 = ceil(get_range_average($conn, $prodID2, $yearAgo, $dateNow));

                              $mlt2 = get_maxlead($conn, $prodID2);

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;
                            if ($needed2 <0) {
                              $needed2 = 0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_range_average($conn, $prodID, $yearAgo, $dateNow));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed<0) {
                                $needed = 0;
                              }

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }

                        }
                        elseif($example==3){
                          //Past 6 Months
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count = 0;
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
                              $ave2 = ceil(get_range_average($conn, $prodID2, $halfYearAgo, $dateNow));

                              $mlt2 = get_maxlead($conn, $prodID2);

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;

                            if ($needed2<0) {
                              $needed2=0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_range_average($conn, $prodID, $halfYearAgo, $dateNow));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed<0) {
                                $needed=0;
                              }
                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }
                        elseif($example==4){
                          //Past 3 Months
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count = 0;
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
                              $ave2 = ceil(get_range_average($conn, $prodID2, $threeMonthsAgo, $dateNow));

                              $mlt2 = get_maxlead($conn, $prodID2);

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;
                            if ($needed2<0) {
                              $needed2=0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_range_average($conn, $prodID, $threeMonthsAgo, $dateNow));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed<0) {
                                $needed=0;
                              }
                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }
                        elseif($example==5){
                          //Seasonality
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count = 0;
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
                              $ave2 = ceil(get_monthly($conn, $prodID2, $thisMonth));

                              $mlt2 = get_maxlead($conn, $prodID2);

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;
                            if ($needed2<0) {
                              $needed2=0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_monthly($conn, $prodID, $thisMonth));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed<0) {
                                $needed=0;
                              }
                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }
                        elseif($example==6){
                          //This Year's Average
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count = 0;
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
                              $ave2 = ceil(get_yearly($conn, $prodID2, $thisYear));

                              $mlt2 = get_maxlead($conn, $prodID2);

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;

                            if ($needed2<0) {
                              $needed2=0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_yearly($conn, $prodID, $thisYear));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed <0) {
                                $needed =0;
                              }
                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }
                        elseif($example==7){
                          //Last Year's Average
                          $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');
                          $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');

                          // FOR WARNINGS COLLAPSE
                          echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";

                            echo "<div class='collapse show' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                            $count = 0;
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
                              $ave2 = ceil(get_yearly($conn, $prodID2, $lastYear));

                              $mlt2 = get_maxlead($conn, $prodID2);

                              $reorderpoint2 = ceil(($ave2*$mlt2)+100);

                              $recipeQuantity2 = $prod2['quantity'];

                              $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;
                            if ($needed2 <0) {
                              $needed2 =0;
                            }
                            if ($total2>$qty2){
                              echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                              echo ceil($needed2);
                              echo " of ";
                              echo $name2;
                              echo " to maintain optimal quantity </div>";
                              $count++;
                          }

                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
                          echo "</div>";
                          echo "</div>";



                            while($row = mysqli_fetch_array($result)){
                              $id = $row['id'];
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];
                              $products = get_ingredients($conn, $id);
                              $total = 0;
                              $unit;
                              $inNeeded = 0;
                              foreach($products as $prod){
                                $prodID = $prod['pid'];
                                $ave = ceil(get_yearly($conn, $prodID, $lastYear));

                                $mlt = get_maxlead($conn, $prodID);

                                $reorderpoint = ceil(($ave*$mlt)+100);

                                $recipeQuantity = $prod['quantity'];

                                $inNeeded = ceil($recipeQuantity*$reorderpoint);

                                $total += $inNeeded;
                              }
                              $needed = $total-$qty;

                              if ($needed <0) {
                                $needed = 0;
                              }
                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo abs(ceil($qty));
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.ceil($needed).'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }

                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
