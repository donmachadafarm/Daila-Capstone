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
                  View Inventory
              </h1>
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

                            $dateNow = date("Y-m-d");
                            $monthAgo = date("Y-m-d", strtotime("-1 year"));
//                            print_p($dateNow);
//                            print_p($monthAgo);

                            $allInventory = mysqli_query($conn, "SELECT product.name AS productname, 
                                                                product.quantity AS quantity, 
                                                                productType.name AS producttypename, 
                                                                product.productPrice, 
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                GROUP BY product.name
                                                                ");

                            $result = mysqli_query($conn,"SELECT product.name AS productname, 
                                                                product.quantity AS quantity, 
                                                                productType.name AS producttypename, 
                                                                product.productPrice, 
                                                                product.productID AS ID,
                                                                MAX(supplier.duration) AS maxLead,
                                                                ROUND(AVG(productsales.quantity)) AS restock
                                                                FROM productsales
                                                                JOIN product on productsales.productID=product.productID
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                JOIN sales ON  productsales.salesID=sales.salesID
                                                                JOIN recipe on product.productID=recipe.productID
                                                                JOIN rmingredient on recipe.ingredientID=rmingredient.ingredientID
                                                                JOIN rawmaterial on rmingredient.rawMaterialID=rawmaterial.rawMaterialID
                                                                JOIN supplier on rawmaterial.supplierID=supplier.supplierID
                                                                GROUP BY product.name
                                                                ");

                            while($row = mysqli_fetch_array($allInventory)){

                                $id = $row['ID'];
                                $prodName = $row['productname'];
                                $prodType = $row['producttypename'];
                                $quantity = $row['quantity'];
                                $price = $row['productPrice'];
                                $restockingValue = 100;

                              while ($row2 = mysqli_fetch_array($result)){
                                  $id2 = $row2['ID'];
                                  $prodName2 = $row2['productname'];
                                  $prodType2 = $row2['producttypename'];
                                  $quantity2 = $row2['quantity'];
                                  $price2 = $row2['productPrice'];
                                  $reorderPoint = ($row2['restock']/12)*100;
                                  $leadTIme = $row2['maxLead'];
                                  $thisValue;

                                  if ($reorderPoint*$leadTIme>$quantity2){
                                      $thisValue = $reorderPoint*$leadTIme;
                                      $restockingValue = $thisValue;
                                      echo '<div class="alert alert-warning"><strong>Warning!</strong> Product ';
                                      echo $prodName2;
                                      echo ' has reached optimal restocking point. Restocking recommended. Recommended level: ';
                                      echo $restockingValue;
                                      echo '</div>';
                                  }

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
                                      echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$restockingValue.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
                                    echo '</td>';
                                  echo '</tr>';


                            }

                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div>
</div>
<br><br><br>

<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
