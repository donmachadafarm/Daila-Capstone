<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }
  $example = 1;
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
                            $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                                                 ORDER BY ingredient.ingredientID');


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
                              if ($total>$qty){
                                echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Ingredient ';
                                echo $name;
                                echo ' to ';
                                echo $total;
                                echo '. Need ';
                                echo $total-$qty;
                                echo ' ';
                                echo $uom;
                                echo 's more ';
                                echo '</div>';
                            }

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
                                      echo '<a href="makeFilteredPO.php?ids='.$id.'&name='.$name.'&val='.$inNeeded.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }


                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
