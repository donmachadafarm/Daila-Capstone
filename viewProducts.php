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
                  View Products
              </h1>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-10">
                    <table class="table table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Type</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $result = mysqli_query($conn,'SELECT product.name AS productname,
                                                                 product.quantity AS quantity,
                                                                 product.description,
                                                                 productType.name AS producttypename
                                                          FROM product
                                                          INNER JOIN productType ON product.productTypeID=productType.productTypeID');


                            while($row = mysqli_fetch_array($result)){

                              $prodName = $row['productname'];
                              $prodType = $row['producttypename'];
                              $quantity = $row['quantity'];
                              // $price = $row['price'];

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $prodName;
                                    echo '<td>';
                                      echo $quantity;
                                    echo '<td>';
                                      echo $prodType;
                                    echo'</td>';
                                    echo '<td>';
                                      echo "0";//initial wala pa ung price column eh
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
