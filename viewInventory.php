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
                Finished Goods Inventory
            </h1>
            <label for="chooseAlgo">Choose Forecasting Algorithm:</label>
            <form method="post">
              <div class="row">
                <div class="col-md-5">
                  <select class="form-control" id="chooseAlgo" name="example">
                      <option value="1" <?php if($example == '1') { ?> selected <?php } ?>>Total Average - All Sales Recorded</option>
                      <option value="2" <?php if($example == '2') { ?> selected <?php } ?>>Yearly Average - Past 1 Year</option>
                      <option value="3" <?php if($example == '3') { ?> selected <?php } ?>>6 Month Average - Past 6 Months</option>
                      <option value="4" <?php if($example == '4') { ?> selected <?php } ?>>3 Month Average - Past 3 Months</option>
                      <option value="5" <?php if($example == '5') { ?> selected <?php } ?>>Seasonality - All Sales from the Month of <?php echo $thisMonthWord?></option>

                  </select>
                </div>
                <div class="col-md-2">
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

                        if ($reorderPoint>$quantity){
                            echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                            echo $prodName;
                            echo ' to ';
                            echo $reorderPoint;
                            echo '</div>';
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
                        echo '</td>';
                        echo '</tr>';

                    }
                }

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

                        if ($reorderPoint>$quantity){
                            echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                            echo $prodName;
                            echo ' to ';
                            echo $reorderPoint;
                            echo '</div>';
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

                        if ($reorderPoint>$quantity){
                            echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                            echo $prodName;
                            echo ' to ';
                            echo $reorderPoint;
                            echo '</div>';
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

                        if ($reorderPoint>$quantity){
                            echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                            echo $prodName;
                            echo ' to ';
                            echo $reorderPoint;
                            echo '</div>';
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

                        if ($reorderPoint>$quantity){
                            echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                            echo $prodName;
                            echo ' to ';
                            echo $reorderPoint;
                            echo '</div>';
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

                        if ($reorderPoint>$quantity){
                            echo '<div class="alert alert-warning"><strong>Warning!</strong> Restock Product ';
                            echo $prodName;
                            echo ' to ';
                            echo $reorderPoint;
                            echo '</div>';
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
                        echo '</td>';
                        echo '</tr>';

                    }
                }

                ?>
                </tbody></table>

        </div>
    </div>
</div>
<br><br><br>

<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
