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
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  View Ingredients
              </h1>
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
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $result = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID),
                                                                 Ingredient.name AS name,
                                                                 Ingredient.quantity AS quantity,
                                                                 RawMaterial.unitOfMeasurement AS uom
                                                                 FROM Ingredient
                                                                 JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                                                 JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID');


                            while($row = mysqli_fetch_array($result)){
                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $uom = $row['uom'];

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
