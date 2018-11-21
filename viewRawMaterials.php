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
                  View Raw Materials
              </h1>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
                    <table class="table table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Supplied RawMaterial</th>
                                <th>Matching Ingredient</th>
                                <th>Price per Unit</th>
                                <th>UOM</th>
                                <th>Type</th>
                                <th>Delivery Days</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $result = mysqli_query($conn,'SELECT RawMaterial.name AS name,
                                                                 RawMaterial.unitOfMeasurement AS uom,
                                                                 RawMaterialType.name AS typename,
                                                                 RawMaterial.pricePerUnit AS price,
                                                                 Supplier.name AS suppName,
                                                                 Supplier.duration AS days,
                                                                 Ingredient.name AS ingname
                                                          FROM RawMaterial
                                                          INNER JOIN RawMaterialType ON RawMaterial.rawMaterialTypeID=RawMaterialType.rawMaterialTypeID
                                                          INNER JOIN Supplier ON Supplier.supplierID=Rawmaterial.supplierID
                                                          INNER JOIN RMIngredient ON RawMaterial.rawMaterialID=RMIngredient.rawMaterialID
                                                          INNER JOIN Ingredient ON Ingredient.ingredientID=RMIngredient.ingredientID');


                            while($row = mysqli_fetch_array($result)){
                              $ingname = $row['ingname'];
                              $name = $row['name'];
                              $price = $row['price'];
                              $uom = $row['uom'];
                              $supp = $row['suppName'];
                              $days = $row['days'];
                              $type = $row['typename'];

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $supp;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $ingname;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $price;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $uom;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $type;
                                    echo'</td>';
                                    echo '<td>';
                                      echo $days;
                                    echo'</td>';
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
