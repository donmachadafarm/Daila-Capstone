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
  $date = date('M-d-Y');

  for ($i=0; $i < count($_POST['supplier']); $i++) {
    // get deadline from the orderid
    $query = "SELECT * FROM Supplier WHERE supplierID = '$sup[$i]'";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $duration = $row['duration'];

      $deadline = date('Y-m-d', strtotime($date. ' + '.$duration.' days'));

    // get the rawmat details (total price->qty*pricePerUnit,unit of measurement, rawmatID)
    $query = "SELECT RawMaterial.pricePerUnit,
                     RawMaterial.unitOfMeasurement,
                     RawMaterial.rawMaterialID
              FROM RawMaterial
              JOIN RMIngredient ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
              WHERE RawMaterial.supplierID = '$sup[$i]' &&  RMIngredient.ingredientID = '$ing[$i]'";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $total = $row['pricePerUnit'] * $qty[$i];

      $uom = $row['unitOfMeasurement'];

      $rmid = $row['rawMaterialID'];

    // insert query for PO
    $query = "INSERT INTO PurchaseOrder (supplierID,totalPrice,orderDate,status,deadline,createdBy)
                VALUES ('{$sup[$i]}','{$total}','{$today}','Pending','{$deadline}','{$user}')";

      $sql = mysqli_query($conn,$query);

    // get the PO details for POItem insert
    $query = "SELECT * FROM PurchaseOrder ORDER BY purchaseOrderID DESC LIMIT 1 ";

      $sql = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($sql);

      $poid = $row['purchaseOrderID'];

    $query = "INSERT INTO POItem (purchaseOrderID,rawMaterialID,quantity,subTotal,unitOfMeasurement,status)
                VALUES('$poid','$rmid','$qty[$i]','$total','$uom','Not Delivered')";

      if(mysqli_query($conn,$query)){
        echo "<script>
          alert('Purchase Order/s Added!');
           window.location.replace('viewPurchaseOrders.php');
              </script>";
      }else {
        echo "<script>alert('Failed!')</script>";
      }
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

              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-lg-12">
          <hr class="style1"><br>
          <h2 class="text-center">Supplier info</h2>
          <table class="table table-hover" id="dataTables-example">
              <thead>
                  <tr>
                    <th>Matching Ingredient</th>

                      <th>Supplier Name</th>
                      <th>Supplied RawMaterial</th>
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
                          echo $ingname;
                        echo '</td>';
                          echo '<td>';
                            echo $supp;
                          echo '</td>';
                          echo '<td>';
                            echo $name;
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

            <?php }else{ ?>
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

                        $arr = array();
                        // print_p($inv);
                        //iterrate per product ingredient
                        for ($i=0; $i < $count; $i++) {
                          //iterrate per product ingredient deets
                          for ($j=0; $j < count($inv[$i]); $j++) {
                            $ing = $inv[$i][$j]['ingredientid'];
                            $cur = $inv[$i][$j]['currentInventory'];
                            $pro = $inv[$i][$j]['productid'];
                            $nid = $inv[$i][$j]['needquantityforPO'];
                            $arr[] = $inv[$i][$j]['ingredientid'];
                            $need = ($nid-$cur);

                            echo "<div class='row'>";
                              echo "<input class='form-control' type = 'hidden' name = 'orderid' value = '".$id."'>";
                              echo "<div class='col'>";
                                echo get_prodname($conn,$pro);
                              echo "</div>";

                              echo "<div class='col'>";
                                echo get_ingname($conn,$ing);
                                echo "<input class='form-control' name ='ing[]' value = '".$ing."' type = 'hidden'>";
                              echo "</div>";

                              echo "<div class='col'>";
                                if($cur <0){
															echo ceil($need-1);
														}
														else{
															echo ceil($need);
														}
                                echo "<input class='form-control' name ='qty[]' value = '". ceil($need) ."' type='hidden'>";
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

                    <?php

                    $suparr = array();

                    foreach ($arr as $key => $value) {
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
                                                    WHERE Ingredient.ingredientID = '$value'
                                                    ORDER BY price ASC");

                       while($p = mysqli_fetch_array($result)){
                        $suparr[] = $p;
                       }
                    }




                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                    <hr class="style1"><br>
                    <h2 class="text-center">Supplier info</h2>
                    <table class="table table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Matching Ingredient</th>
                                <th>Supplier Name</th>
                                <th>Supplied RawMaterial</th>

                                <th>Price per Unit</th>
                                <th>UOM</th>
                                <th>Delivery Days</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $tar = array();

                            foreach ($suparr as $key => $value) {
                              $tar[] = $value;
                            }

                              // print_p($tar);
                              for ($i=0; $i < count($tar); $i++) {

                                $ingname = $tar[$i]['ingname'];
                                $name = $tar[$i]['name'];
                                $price = $tar[$i]['price'];
                                $uom = $tar[$i]['uom'];
                                $supp = $tar[$i]['suppName'];
                                $days = $tar[$i]['days'];


                                    echo '<tr>';
                                    echo '<td>';
                                      echo $ingname;
                                    echo '</td>';
                                      echo '<td>';
                                        echo $supp;
                                      echo '</td>';
                                      echo '<td>';
                                        echo $name;
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
                  <?php } ?>


                  </div>
              </div>
          </div>
      </div>



  </div>



<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
