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
  $val = $_POST['restockQuantity'];
  $ing = $_POST['ingredient'];
  $date = date('M-d-Y');
  $query = "SELECT supplier.duration AS 'duration'
            FROM supplier 
            WHERE supplier.company = '$sup'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_array($result);
  $duration = $row['duration'];
  $deadline = date('Y-m-d', strtotime($date. ' + ' .$duration.' days'));

  //get wanted rawmat - ingredient
  $rmquery = "SELECT RawMaterial.pricePerUnit as 'price',
  RawMaterial.unitOfMeasurement as 'uom',
  RawMaterial.rawMaterialID as 'rmid'
  FROM RawMaterial
  JOIN RMIngredient ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
  WHERE RawMaterial.supplierID = '$sup' &&  RMIngredient.ingredientID = '$ing'";

  $rmsql = mysqli_query($conn, $rmquery);
  $rmrow = mysqli_fetch_array($rmsql);

  $total = $rmrow['price'] * $val;
  $uom = $rmrow['uom'];
  $rmid = $rmrow['rmid'];

  // insert purchase order
  $query2 = "INSERT into PurchaseOrder (supplierID,totalPrice,orderDate,status,deadline,createdBy) values ('{$sup}','{$total}','{$today}','Pending','{$deadline}','{$user}')";

  mysqli_query($conn, $query2);

  // get the PO details for POItem insert
  $query3 = "SELECT * FROM PurchaseOrder ORDER BY purchaseOrderID DESC LIMIT 1 ";

  $sql3 = mysqli_query($conn,$query3);

  $row3 = mysqli_fetch_array($sql3);

  $poid = $row3['purchaseOrderID'];

$query = "INSERT INTO POItem (purchaseOrderID,rawMaterialID,quantity,subTotal,unitOfMeasurement,status)
            VALUES('$poid','$rmid','$val','$total','$uom','Not Delivered')";

if(mysqli_query($conn,$query3)){
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
                        <form action="makeFilteredPO.php" method="POST">
                          <div class='row'>
                              <div class='col'>Ingredient:</div>
                              <div class='col'>Quantity:</div>
                              <div class='col'>Supplier:</div>
                              <div class='col'></div>
                          </div>
                          <div class = 'row'>
                          <input class='form-control' type = 'hidden' name = 'ingredient' value = <?php $ingID ?>>
                              <div class='col'><?php echo $ingName ?></div>
                              <input class='form-control' type = 'hidden' name = 'restockQuantity' value = <?php $restockQuantity ?>>
                              <div class='col'><?php echo $restockQuantity?> <?php echo $unit ?>s</div>
                              <div class='col'>
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
                              <div class='col'> 
                              
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
