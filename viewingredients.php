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
                <select class="form-control" id="chooseAlgo" name="example">
                    <option value="1" <?php if($example == '1') { ?> selected <?php } ?>>Total Average - All Sales Recorded</option>
                    <option value="2" <?php if($example == '2') { ?> selected <?php } ?>>Yearly Average - Past 1 Year</option>
                    <option value="3" <?php if($example == '3') { ?> selected <?php } ?>>6 Month Average - Past 6 Months</option>
                    <option value="4" <?php if($example == '4') { ?> selected <?php } ?>>3 Month Average - Past 3 Months</option>
                    <option value="5" <?php if($example == '5') { ?> selected <?php } ?>>Seasonality - All Sales from the Month of <?php echo $thisMonthWord?></option>

                </select>
                <input type="submit" name="choose" id="submitButton" value="Submit">
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

                            echo "<div class='collapse' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                          while($row2 = mysqli_fetch_array($result2)){
                            $id2 = $row2['id'];
                            $name2 = $row2['name'];
                            $qty2 = $row2['quantity'];
                            $uom2 = $row2['uom'];
                            $products2 = get_ingredients($conn, $id2);
                            $total2 = 0;
                            $unit2;
                            $inNeeded2 = 0;
                            $count=0;

                            // set collapse box for notifs
                            
                            foreach($products2 as $prod2){
                              $prodID2 = $prod2['pid'];
                              $ave2 = get_total_average($conn, $prodID2);
                              
                              $mlt2 = get_maxlead($conn, $prodID2);
                              
                              $reorderpoint2 = ($ave2*$mlt2)+100;
                              
                              $recipeQuantity2 = $prod2['quantity'];
                              
                              $inNeeded2 = $recipeQuantity2*$reorderpoint2;
                              
                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;
                            if ($total2>$qty2){
                              echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Ingredient ';
                              echo $name2;
                              echo " to ";
                              echo $total2;
                              echo ". Need ";
                              echo $needed2;
                              echo " ";
                              echo $uom2;
                              echo "s more. ";
                              echo '<a href="makeFilteredPO.php?ids='.$id2.='&val='.$needed2.'&unit='.$uom2.'">Restock</a>';
                              echo "</div>";
                              $count++;
                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
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
                              $needed = $total-$qty;

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $qty;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.$needed.'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
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

                            echo "<div class='collapse' id='collapseExample'>";
                            echo "<div class='card card-body'>";
                          while($row2 = mysqli_fetch_array($result2)){
                            $id2 = $row2['id'];
                            $name2 = $row2['name'];
                            $qty2 = $row2['quantity'];
                            $uom2 = $row2['uom'];
                            $products2 = get_ingredients($conn, $id2);
                            $total2 = 0;
                            $unit2;
                            $inNeeded2 = 0;
                            $count=0;

                            // set collapse box for notifs
                            
                            foreach($products2 as $prod2){
                              $prodID2 = $prod2['pid'];
                              $ave2 = get_total_average($conn, $prodID2);
                              
                              $mlt2 = get_maxlead($conn, $prodID2);
                              
                              $reorderpoint2 = ($ave2*$mlt2)+100;
                              
                              $recipeQuantity2 = $prod2['quantity'];
                              
                              $inNeeded2 = $recipeQuantity2*$reorderpoint2;
                              
                              $total2 += $inNeeded2;
                            }
                            $needed2 = $total2-$qty2;
                            if ($total2>$qty2){
                              echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Ingredient ';
                              echo $name2;
                              echo " to ";
                              echo $total2;
                              echo ". Need ";
                              echo $needed2;
                              echo " ";
                              echo $uom2;
                              echo "s more. ";
                              echo '<a href="makeFilteredPO.php?ids='.$id2.='&val='.$needed2.'&unit='.$uom2.'">Restock</a>';
                              echo "</div>";
                              $count++;
                          }
                          if($count==0){
                            echo "<h3>No Warnings to Show</h3>";
                          }
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
                              $needed = $total-$qty;

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $qty;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                      echo '<a href="makeFilteredPO.php?ids='.$id.='&val='.$needed.'&unit='.$uom.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }
                        }
                        elseif($example==2){
                          //Past 1 Year
                        }
                        elseif($example==3){
                          //Past 6 Months
                        } 
                        elseif($example==4){
                          //Past 3 Months
                        } 
                        elseif($example==5){
                          //Seasonality
                        }

                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
