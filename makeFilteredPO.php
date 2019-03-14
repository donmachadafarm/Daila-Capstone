<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }
?>

<?php

if(isset($_GET['ids'])){
  $ingID = $_GET['ids'];
  $ingName = get_ingname($conn, $ingID);
  $restockQuantity = $_GET['val'];
  $unit = $_GET['unit'];
}

// Get date today
$today = date("Y-m-d");

// Get userid
$user = $_SESSION['userid'];

if (isset($_POST['submit'])){
      $sup = $_POST['supplier'];
      $unit = $_POST['unit'];
      $qty = $_POST['restockQuantity'];
      $ing = $_POST['ingredient'];
      $date = date('Y-m-d');
      $total = 0;

      $duration = get_suppdur($conn,$sup);
      $deadline = date('Y-m-d', strtotime($date. ' + ' .$duration.' days'));

      $query = "SELECT RawMaterial.pricePerUnit,
                       RawMaterial.unitOfMeasurement,
                       RawMaterial.rawMaterialID
                FROM RawMaterial
                JOIN RMIngredient ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                WHERE RawMaterial.supplierID = '$sup' &&  RMIngredient.ingredientID = '$ing'";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

      $total = $row['pricePerUnit'] * $qty;

      $uom = $row['unitOfMeasurement'];

      $rmid = $row['rawMaterialID'];

      // insert purchase order
      $query2 = "INSERT INTO PurchaseOrder (supplierID,totalPrice,orderDate,status,deadline,createdBy)
                  VALUES ('{$sup}','{$total}','{$today}','Pending','{$deadline}','{$user}')";

        mysqli_query($conn, $query2);

      // get the PO details for POItem insert
      $query3 = "SELECT * FROM PurchaseOrder ORDER BY purchaseOrderID DESC LIMIT 1 ";

      $sql3 = mysqli_query($conn,$query3);

      $row3 = mysqli_fetch_array($sql3);

      $poid = $row3['purchaseOrderID'];

      $query = "INSERT INTO POItem (purchaseOrderID,rawMaterialID,quantity,subTotal,unitOfMeasurement,status)
                VALUES('$poid','$rmid','$qty','$total','$uom','Not Delivered')";

    if(mysqli_query($conn,$query)){
      echo "<script>
        alert('Purchase Order/s Added!');
         window.location.replace('viewPurchaseOrders.php');
            </script>";
    }else {
      echo "<script>alert('Failed!')</script>";
    }

}


?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Purchase Order
              </h1>
          </div>
      </div>
      <?php if(isset($_GET['ids'])){ ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-body"><br>
                        <form action="" method="POST">
                          <div class='row'>
                              <div class='col'><b>Ingredient:</b></div>
                              <div class='col'><b>Quantity:</b></div>
                              <div class='col'><b>Supplier:</b></div>
                              <div class='col'></div>
                          </div>
                          <div class = 'row'>
                          <input type = 'hidden' name = 'ingredient' value ="<?php echo $ingID; ?>">
                          <input type = "hidden" name="unit" value="<?php echo $unit ?>">
                              <div class='col-md-3'>
                                <input class="form-control-plaintext" type="text" name="" value="" placeholder="<?php echo $ingName; ?>" readonly>
                              </div>
                              <div class='col-md-3'>
                                <input class='form-control' type = 'number' min="0" name = 'restockQuantity' value ="<?php echo $restockQuantity; ?>">
                              </div>
                              <div class='col-md-4'>
                                <select class = "form-control" name = "supplier">
                                  <?php
                                    $query = "SELECT supplier.company as supplierName, supplier.supplierID as supplierID
                                              FROM ingredient
                                              JOIN rmingredient on ingredient.ingredientID = rmingredient.ingredientID
                                              JOIN rawmaterial on rmingredient.rawMaterialID = rawmaterial.rawMaterialID
                                              JOIN supplier on rawmaterial.supplierID = supplier.supplierID
                                              WHERE ingredient.ingredientID = '$ingID'";
                                    $supplierList = mysqli_query($conn, $query);

                                    while($next = mysqli_fetch_array($supplierList)){
                                      echo "<option value='$next[supplierID]'>".$next['supplierName']."</option>";
                                    }
                                  ?>

                                </select>
                              </div>

                              <div class='col-md-2'>

                              <input type="submit" name="submit" value="Proceed" class="btn btn-success"/>

                              </div>

                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      <?php } ?>
      <!--                                                                                   -->
      <!--                                                                                   -->
      <!--                                                                                   -->
      <!--                               N O T    I N C L U D E D                            -->
      <!--                                                                                   -->
      <!--                                                                                   -->
      <!--                                                                                   -->
      <!--                                                                                   -->
      <!--                                                                                   -->
      <!--                                                                                   -->

      <hr class="style1"><br>
      <h2 class="text-center">Supplier info</h2>
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

                                <th>Delivery Days</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php

                          if(isset($_GET['ids'])){
                            $result = mysqli_query($conn,"SELECT RawMaterial.name AS name,
                                                                 RawMaterial.unitOfMeasurement AS uom,

                                                                 RawMaterial.pricePerUnit AS price,
                                                                 Supplier.company AS suppName,
                                                                 Supplier.duration AS days,
                                                                 Ingredient.name AS ingname
                                                          FROM RawMaterial

                                                          INNER JOIN Supplier ON Supplier.supplierID=Rawmaterial.supplierID
                                                          INNER JOIN RMIngredient ON RawMaterial.rawMaterialID=RMIngredient.rawMaterialID
                                                          INNER JOIN Ingredient ON Ingredient.ingredientID=RMIngredient.ingredientID
                                                          WHERE ingredient.ingredientID = '$ingID'");


                            while($row = mysqli_fetch_array($result)){
                              $ingname = $row['ingname'];
                              $name = $row['name'];
                              $price = $row['price'];
                              $uom = $row['uom'];
                              $supp = $row['suppName'];
                              $days = $row['days'];


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
                                      echo $days;
                                    echo'</td>';
                                  echo '</tr>';


                            }
                          }

                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div><br><br>
  </div>



<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
