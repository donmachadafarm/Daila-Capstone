<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}
?>

<style>
  .hide{
    display:none;
  }
  .show{
    display:block;
  }
</style>

<?php
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


if (isset($_POST['edit'])) {
  $id = $_POST['prodid'];
  $qty = $_POST['qty'];
  $remarks = $_POST['remarks'];

  update_inventory($conn,$id,$qty,$remarks);
}
?>

<!-- put all the contents here  -->


<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Finished Goods Inventory
            </h1>
            <label for="chooseAlgo">Choose Forecasting Method:</label>
            <form method="post">
            <div class="row">
                <div class = "col-md-5">
                    <select class="form-control" id="chooseAlgo" name="example">
                        <option value="1" <?php if($example == '1') { ?> selected <?php } ?>>Total Average - All Sales Recorded</option>
                        <option value="2" <?php if($example == '2') { ?> selected <?php } ?>>Yearly Average - Past 1 Year</option>
                        <option value="3" <?php if($example == '3') { ?> selected <?php } ?>>6 Month Average - Past 6 Months</option>
                        <option value="4" <?php if($example == '4') { ?> selected <?php } ?>>3 Month Average - Past 3 Months</option>
                        <option value="5" <?php if($example == '5') { ?> selected <?php } ?>>Seasonality - All Sales from the Month of <?php echo $thisMonthWord; ?></option>
                        <option value="6" <?php if($example == '6') { ?> selected <?php } ?>>This Year's Average - All Sales from the Year of <?php echo $thisYear; ?></option>
                        <option value="7" <?php if($example == '7') { ?> selected <?php } ?>>Last Year's Average - All Sales from the Year of <?php echo $lastYear; ?></option>
                        <option value="8" <?php if($example == '8') { ?> selected <?php } ?>>Weighted Average</option>

                    </select>
                </div>
                <div class ="col-md-2">
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
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if (!isset($_POST['choose'])){

                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse' id='collapseExample'>";
                    echo "<div class='card card-body'>";
                    $count = 0;

                    $arr = array();
                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_total_average($conn, $id);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                            array_push($arr,$id);
                            echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                            echo $needed;
                            echo " of ";
                            echo $prodName;
                            echo " to maintain optimal quantity </div>";
                            $count++;
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_total_average($conn, $id);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;
                        $extra = 1;

                        if($needed > 100){
                            $extra = ceil($needed * .1);
                        }

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                          if (in_array($id,$arr)) {
                            echo '<td class="table-danger"><a href="viewIndivProduct.php?id='.$id.'" style="color: #000;text-decoration: none;">';
                              echo $prodName;
                            echo '</a></td>';

                            echo '<td class="table-danger">';
                              echo $quantity;
                            echo '</td>';

                            echo '<td class="table-danger">';
                              echo $prodType;
                            echo'</td>';

                            echo '<td class="table-danger">';
                              echo $price;
                            echo'</td>';

                            echo '<td class="text-center table-danger">';
                              echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-success btn-sm">Restock</button> </a> ';
                                if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                                  echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                          Phyiscal Count
                                          </a>";

                                  ?>
                                  <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                          <form method="post">
                                              <div class="modal-content">

                                                  <div class="modal-header">
                                                      <h4>Edit inventory</h4>
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                  </div>

                                                  <div class="modal-body">
                                                      <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                                      <div class="text-center">
                                                        <p>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label class="col-sm-2 col-form-label">Quantity:</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                            </div>
                                                          </div><br>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label class="col-sm-2 col-form-label">Remarks:</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                          </div>
                                                        </p>
                                                      </div>
                                                      <div class="modal-footer">
                                                          <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                          <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                                      </div>
                                                  </div>
                                          </form>
                                          </div>
                                      </div>
                                  </div>
                                  <?php
                                }

                            echo '</td>';
                          }else {
                            echo '<td><a href="viewIndivProduct.php?id='.$id.'" style="color: #000;text-decoration: none;">';
                              echo $prodName;
                            echo '</a></td>';

                            echo '<td>';
                              echo $quantity;
                            echo '</td>';

                            echo '<td>';
                              echo $prodType;
                            echo'</td>';

                            echo '<td>';
                              echo $price;
                            echo'</td>';

                            echo '<td class="text-center">';
                              echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-success btn-sm">Restock</button> </a> ';
                                if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                                  echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                          Phyiscal Count
                                          </a>";

                                  ?>
                                  <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                          <form method="post">
                                              <div class="modal-content">

                                                  <div class="modal-header">
                                                      <h4>Edit inventory</h4>
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                  </div>

                                                  <div class="modal-body">
                                                      <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                                      <div class="text-center">
                                                        <p>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label class="col-sm-2 col-form-label">Quantity:</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                            </div>
                                                          </div><br>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label class="col-sm-2 col-form-label">Remarks:</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                          </div>
                                                        </p>
                                                      </div>
                                                      <div class="modal-footer">
                                                          <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                          <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                                      </div>
                                                  </div>
                                          </form>
                                          </div>
                                      </div>
                                  </div>
                                  <?php
                                }

                            echo '</td>';
                          }
                        echo '</tr>';

                    }




                }

                // end first conditional

                elseif ($example ==1){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_total_average($conn, $id);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_total_average($conn, $id);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                                  </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }

                elseif ($example == 2){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_range_average($conn, $id, $yearAgo, $dateNow);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_range_average($conn, $id, $yearAgo, $dateNow);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                                  </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }

                elseif ($example == 3){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_range_average($conn, $id, $halfYearAgo, $dateNow);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_range_average($conn, $id, $halfYearAgo, $dateNow);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                                  </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }

                elseif ($example == 4){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_range_average($conn, $id, $threeMonthsAgo, $dateNow);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_range_average($conn, $id, $threeMonthsAgo, $dateNow);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                                  </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }

                elseif ($example == 5){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_monthly($conn, $id, $thisMonth);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_monthly($conn, $id, $thisMonth);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                                  </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }
                elseif($example == 6){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_monthly($conn, $id, $thisMonth);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_yearly($conn, $id, $thisYear);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                                  </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }
                elseif($example == 7){
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_monthly($conn, $id, $lastYear);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        if ($reorderPoint>$quantity){
                          echo "<div class='alert alert-warning'><strong>Warning!</strong> order ";
                          echo $needed;
                          echo " of ";
                          echo $prodName;
                          echo " to maintain optimal quantity </div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    while ($row = mysqli_fetch_array($allInventory2)){
                        $id = $row['ID'];
                        $prodName = $row['productname'];
                        $prodType = $row['producttypename'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $restockingValue = 100;
                        $maxLeadTime = get_maxlead($conn, $id);
                        $averageSales = get_yearly($conn, $id, $lastYear);
                        $reorderPoint = 100+($averageSales*$maxLeadTime);
                        $needed = $reorderPoint-$quantity;

                        if ($needed < 0) {
                          $needed = 0;
                        }

                        echo '<tr>';
                        echo '<td><a href="viewIndivProduct.php?id='.$id.'"  style="color: #000;text-decoration: none;">';
                        echo $prodName;
                        echo '</a></td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo $prodType;
                        echo'</td>';
                        echo '<td>';
                        echo $price;
                        echo'</td>';

                        echo '<td class="text-center">';
                        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                                  Phyiscal Count
                                  </a>";

                          ?>
                          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                  <form method="post">
                                      <div class="modal-content">

                                          <div class="modal-header">
                                              <h4>Edit inventory</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <div class="modal-body">
                                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                              <div class="text-center">
                                                <p>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Quantity:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                    </div>
                                                  </div><br>
                                                  <div class="row">
                                                    <div class="col-md-4">
                                                      <label class="col-sm-2 col-form-label">Remarks:</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <textarea name="remarks" rows="3" cols="40" class="form-control" required></textarea>                                                    </div>
                                                  </div>
                                                </p>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                          </div>
                                  </form>
                              </div>
                              </div>
                          </div>
                          <?php
                        }
                        echo '</td>';
                        echo '</tr>';

                    }
                }
                elseif($example==8){

                  if(!isset($_POST['date'])){
                    echo "<a href='setParams.php'><h3>Set Forecast Parameters</h3></a><br>";

                  }

                  else{
                    $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");

                    $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                                                product.quantity AS quantity,
                                                                productType.name AS producttypename,
                                                                product.productPrice,
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                WHERE product.custom <> 1
                                                                GROUP BY product.name
                                                                ");
                    echo "<button class='btn btn-warning' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Restock Warnings</button>";
                    echo "<br><br>";
                    echo "<div class='collapse show' id='collapseExample'>";
                    echo "<div class='card card-body'>";

                    while ($row = mysqli_fetch_array($allInventory)){
                      $id = $row['ID'];
                      $prodName = $row['productname'];
                      $prodType = $row['producttypename'];
                      $quantity = $row['quantity'];
                      $price = $row['productPrice'];
                      $restockingValue = 100;
                      $maxLeadTime = get_maxlead($conn, $id);
                      $date = $_POST['date'];
                      $weight = $_POST['weight'];
                      $averageSales = 0;
                      $reorderPoint = 0;
                      $needed = 0;

                    for($i = 0; $i<count($date); $i++){

                      $month = substr($date[$i], 0, 2);
                      $year = substr($date[$i], 3, 7);
                      $dateWeight = $weight[$i];
                      $averageSales += get_weighted_average($conn, $id, $month, $year, $weight[$i]);
                      // $reorderPoint += 100+($averageSales*$maxLeadTime);
                      // $needed = $reorderPoint-$quantity;
                      echo $id;
                      echo "<br>";
                      echo $month;
                      echo "<br>";
                      echo $year;
                      echo "<br>";
                      echo $dateWeight;
                      echo "<br>";
                      echo $averageSales;
                      echo "<br>";
                    }


                      if ($needed < 0) {
                        $needed = 0;
                      }

                      if ($reorderPoint>$quantity){
                          echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                          echo $prodName;
                          echo ' to ';
                          echo $reorderPoint;
                          echo ' need ' . $needed . ' more';
                          echo '</div>';
                      }

                  }

                  echo "</div>";
                  echo "</div>";

                  while ($row = mysqli_fetch_array($allInventory2)){
                    $id = $row['ID'];
                    $prodName = $row['productname'];
                    $prodType = $row['producttypename'];
                    $quantity = $row['quantity'];
                    $price = $row['productPrice'];
                    $restockingValue = 100;
                    $maxLeadTime = get_maxlead($conn, $id);
                    $date = $_POST['date'];
                      $weight = $_POST['weight'];

                    for($i = 0; $i<count($date); $i++){

                      $month = substr($date[$i], 0, 2);
                      $year = substr($date[$i], 3, 7);
                      $dateWeight = $weight[$i];
                      $averageSales += get_weighted_average($conn, $id, $month, $year, $weight[$i]);
                      $reorderPoint += 100+($averageSales*$maxLeadTime);
                      $needed = $reorderPoint-$quantity;
                    }

                    if ($needed < 0) {
                      $needed = 0;
                    }

                    echo '<tr>';
                    echo '<td><a href="viewIndivProduct.php?id='.$id.'">';
                    echo $prodName;
                    echo '</a></td>';
                    echo '<td>';
                    echo $quantity;
                    echo '</td>';
                    echo '<td>';
                    echo $prodType;
                    echo'</td>';
                    echo '<td>';
                    echo $price;
                    echo'</td>';

                    echo '<td class="text-center">';
                    echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                    if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
                      echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                              Edit
                              </a>";

                      ?>
                      <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                              <form method="post">
                                  <div class="modal-content">

                                      <div class="modal-header">
                                          <h4>Edit inventory</h4>
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      </div>

                                      <div class="modal-body">
                                          <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                          <div class="text-center">
                                            <p>
                                              <div class="row">
                                                <div class="col-md-4">
                                                  <label class="col-sm-2 col-form-label">Quantity:</label>
                                                </div>
                                                <div class="col-md-6">
                                                  <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                                </div>
                                              </div>
                                            </p>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                              <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                          </div>
                                      </div>
                              </form>
                          </div>
                          </div>
                      </div>
                      <?php
                    }
                    echo '</td>';
                    echo '</tr>';

                }

                  }


                }


                ?>
                </tbody></table>

        </div>
    </div>
</div>
<br><br><br>

<!-- end of content -->

<script type="text/javascript">
  $(document).ready(function(){

   $(document).on('click', '.add', function(){
      var html = '';
      html += '<tr>';
      html += '<td><select name="month[]" class="form-control item_unit"><option value="" disabled>Select Month</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select></td>';
      html += '<td><input type="number" name="weight[]" class="form-control item_name" required /></td>';
      html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">x</button></td></tr>';
      $('#item_table').append(html);
     });

     $(document).on('click', '.remove', function(){
      $(this).closest('tr').remove();
     });

  });
</script>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>

<?php include "includes/sections/footer.php"; ?>
