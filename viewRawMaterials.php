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
          <div class="col-lg-10">
                    <table class="table table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>RawMaterial Name</th>
                                <th>Quantity</th>
                                <th>Capacity Per Unit</th>
                                <th>Price per Unit</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $result = mysqli_query($conn,'SELECT RawMaterial.name AS name,
                                                                 RawMaterial.quantity AS quantity,
                                                                 RawMaterial.capacityPerUnit AS capacity,
                                                                 RawMaterial.unitOfMeasurement AS uom,
                                                                 RawMaterialType.name AS typename,
                                                                 RawMaterial.pricePerUnit AS price
                                                          FROM RawMaterial
                                                          INNER JOIN RawMaterialType ON RawMaterial.rawMaterialTypeID=RawMaterialType.rawMaterialTypeID');


                            while($row = mysqli_fetch_array($result)){

                              $name = $row['name'];
                              $qty = $row['quantity'];
                              $cap = $row['capacity'];
                              $price = $row['price'];
                              $uom = $row['uom'];
                              $type = $row['typename'];
                              // $price = $row['price'];

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $qty;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $cap." ".$uom;
                                    echo'</td>';
                                    echo '<td>';
                                      echo $price;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $type;//initial wala pa ung price column eh
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
