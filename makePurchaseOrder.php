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

// Get date today
$today = date("Y-m-d");

// Get userid
$user = $_SESSION['userid'];

if (isset($_POST['submit'])){
  $ing = $_POST['ing'];
  $qty = $_POST['qty'];
  $sup = $_POST['supplier'];
  $id = $_POST['orderid'];

  for ($i=0; $i < count($_POST['supplier']); $i++) {
    // get deadline from the orderid
    $query = "SELECT * FROM JobOrder WHERE orderID = '$id'";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $deadline = $row['dueDate'];

    // get the rawmat details (total price->qty*pricePerUnit,unit of measurement, rawmatID)
    $query = "SELECT * FROM RawMaterial WHERE supplierID = '$sup[$i]'";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $total = $row['pricePerUnit'] * $qty[$i];

      $uom = $row['unitOfMeasurement'];

      $rmid = $row['rawMaterialID'];

    // insert query for PO
    $query = "INSERT INTO PurchaseOrder (supplierID,totalPrice,orderDate,status,deadline,createdBy) values ('{$sup[$i]}','{$total}','{$today}','Pending','{$deadline}','{$user}')";

      $sql = mysqli_query($conn,$query);

    // get the PO details for POItem insert
    $query = "SELECT * FROM PurchaseOrder ORDER BY purchaseOrderID DESC LIMIT 1 ";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $poid = $row['purchaseOrderID'];

    $query = "INSERT INTO POItem (purchaseOrderID,rawMaterialID,quantity,subTotal,unitOfMeasurement,status) VALUES('$poid','$rmid','$qty[$i]','$total','$uom','Not Delivered')";

      $sql = mysqli_query($conn,$query);
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

      <div class="row">
          <div class="col-lg-8">
              <div class="panel panel-default">
                <?php

                if (!isset($_GET['lack'])) {
                  ?>

                  <form action="postPurchaseOrder.php" method="GET">
                     <div class="form-group">
                        <p class="form-control-static">
                              <label>Supplier:</label></br>
                               <select class="form-control" name="supplier" id="supplier-list">
                               <?php
                                 $result = mysqli_query($conn, 'SELECT * FROM Supplier');
                                 while($row = mysqli_fetch_array($result)){
                                   echo "<label><option value=\"{$row['supplierID']}\">{$row['company']}</option></label>
                                   <br>";
                                 }
                                ?>
                              </select><br><small class="form-text text-muted">Not in the list of suppliers? <a href="addSupplier.php">Click here</a></small><br>

                        </p>
                    <input type="submit" name="submit" value="Select Supplier" class="btn btn-success"/></div>
                  </form>

                <?php
                } else{
                ?>
                  <div class="panel-body"><br>
                      <div class='row'>
                          <div class='col'>Product:</div>
                          <div class='col'>Ingredient:</div>
                          <div class='col'>Quantity:</div>
                          <div class='col'>Supplier:</div>
                      </div>
                  </div>


                    <!--PO form-->

                  <form action="makePurchaseOrder.php" method="POST">
                      <div class="form-group">
                          <p class="form-control-static">



                  <?php
                      if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        $inv = get_need_inventory2($conn,$id);
                        $count = count($inv);

                        //
                        for ($i=0; $i < $count; $i++) {
                          for ($j=0; $j < count($inv[$i]); $j++) {
                            $ing = $inv[$i][$j]['ingredientid'];
                            $nid = $inv[$i][$j]['needquantityforPO'];
                            $pro = $inv[$i][$j]['productid'];



                            echo "<div class='row'>";
                              echo "<input type = 'hidden' name = 'orderid' value = '".$id."'>";
                              echo "<div class='col'>";
                                echo get_prodname($conn,$pro);
                              echo "</div>";

                              echo "<div class='col'>";
                                echo get_ingname($conn,$ing);
                                echo "<input name ='ing[]' value = '".$ing."' type = 'hidden'>";
                              echo "</div>";

                              echo "<div class='col'>";
                                echo number_format($nid, 2);
                                echo "<input name ='qty[]' value = '". number_format($nid) ."' type='hidden'>";
                              echo "</div>";

                              echo "<div class='col'>";
                                echo "<select class='form-control' name='supplier[]'>";

                                      $query = "SELECT supplier.company as supplierName, supplier.supplierID as IDofSupplier
                                          from ingredient
                                          join rmingredient on ingredient.ingredientID = rmingredient.ingredientID
                                          join rawmaterial on rmingredient.rawMaterialID = rawmaterial.rawMaterialID
                                          join supplier on rawmaterial.supplierID = supplier.supplierID
                                          WHERE ingredient.ingredientid = '$ing'";
                                      $supplierlist = mysqli_query($conn, $query);

                                      while ($next = mysqli_fetch_array($supplierlist)){
                                          echo "<option value='$next[IDofSupplier]'>".$next['supplierName']."</option>";
                                      }

                                echo "</select>";
                              echo "</div>";
                            echo "</div>";
                          }
                        }
                      }
                       ?>
                  <br>
                    <input type="submit" name="submit" value="Proceed" class="btn btn-success"/></div>
                    </form>
                  <?php } ?>
                  </div>
              </div>
          </div>
      </div>

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
                            $result = mysqli_query($conn,'SELECT RawMaterial.name AS name,
                                                                 RawMaterial.unitOfMeasurement AS uom,

                                                                 RawMaterial.pricePerUnit AS price,
                                                                 Supplier.company AS suppName,
                                                                 Supplier.duration AS days,
                                                                 Ingredient.name AS ingname
                                                          FROM RawMaterial

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


                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div><br><br>
  </div>



<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
