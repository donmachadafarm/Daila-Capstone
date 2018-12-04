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
                            $monthAgo = date("Y-m-d", strtotime("-1 month"));
//                            print_p($dateNow);
//                            print_p($monthAgo);

                            $allInventory = mysqli_query($conn, "SELECT product.name AS productname, 
                                                                product.quantity AS quantity, 
                                                                productType.name AS producttypename, 
                                                                product.productPrice, 
                                                                product.productID AS ID
                                                                FROM product
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                ");

                            $result = mysqli_query($conn,"SELECT product.name AS productname, 
                                                                product.quantity AS quantity, 
                                                                productType.name AS producttypename, 
                                                                product.productPrice, 
                                                                product.productID AS ID,
                                                                ROUND(AVG(productsales.quantity)) AS restock
                                                                FROM productsales
                                                                JOIN product on productsales.productID=product.productID
                                                                JOIN productType ON product.productTypeID=productType.productTypeID
                                                                JOIN sales ON  productsales.salesID=sales.salesID
                                                                WHERE sales.saleDate BETWEEN '$monthAgo' and '$dateNow'
                                                                GROUP BY product.name
                                                                ");

//                            $result2 = mysqli_query($conn, "SELECT product.name,
//                                                                ROUND(AVG(productsales.quantity)) AS restock
//                                                                FROM productsales
//                                                                JOIN product on productsales.productID=product.productID
//                                                                JOIN sales ON  productsales.salesID=sales.salesID
//                                                                WHERE sales.saleDate BETWEEN '$monthAgo' and '$dateNow'
//                                                                GROUP BY product.name");

                            while($row = mysqli_fetch_array($allInventory)){

                                $id = $row['ID'];
                                $prodName = $row['productname'];
                                $prodType = $row['producttypename'];
                                $quantity = $row['quantity'];
                                $price = $row['productPrice'];

                              while ($row2 = mysqli_fetch_array($result)){
                                  $id = $row2['ID'];
                                  $prodName = $row2['productname'];
                                  $prodType = $row2['producttypename'];
                                  $quantity = $row2['quantity'];
                                  $price = $row2['productPrice'];
                                  $reorderPoint = $row2['restock'];

                                  if ($reorderPoint>$quantity){
                                      echo '<div class="alert alert-warning"><strong>Warning!</strong> Product ';
                                      echo $prodName;
                                      echo ' has reached optimal restocking point. Restocking recommended.';
                                      echo $reorderPoint;
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
                                      echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'"><button type="button" class="btn btn-primary btn-sm">Restock</button></a> ';
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
