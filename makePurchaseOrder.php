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
  // Query
  if (isset($_POST['submit'])){

      $rawmat=$_POST['rawmat'];
      $supplier=$_POST['supplier'];
      $quantity=$_POST['quantity'];
      $orderdate=$_POST['orderdate'];

    if(!isset($message)){
      $query="INSERT into PurchaseOrder (rawMaterialID,supplierID,quantity,orderDate) values ('{$rawmat}','{$supplier}','{$quantity}','{$orderdate}')";

        if (mysqli_query($conn,$query)) {

          $query1="SELECT * FROM RawMaterial WHERE rawMaterialID=$rawmat";

            $sql=mysqli_query($conn,$query1);

            $row = mysqli_fetch_array($sql);

            $value = $quantity * $row['capacityPerUnit'];

          $query2="SELECT * FROM RMIngredient WHERE rawMaterialID=$rawmat";

            $sql = mysqli_query($conn,$query2);

            $row = mysqli_fetch_array($sql);

            $id2 = $row['ingredientID'];

          $query3="UPDATE Ingredient i
                    INNER JOIN RMIngredient ON RMIngredient.ingredientID = i.ingredientID
                    INNER JOIN RawMaterial ON RawMaterial.rawMaterialID = RMIngredient.rawMaterialID
                    SET i.quantity = i.quantity + $value
                    WHERE i.ingredientID=$id2";

            $sql = mysqli_query($conn,$query3);

          $query4="UPDATE rawMaterial
                    SET quantity = quantity + $quantity
                    WHERE rawMaterialID=$rawmat";

            $sql = mysqli_query($conn,$query4);

          echo "<script>
            alert('Purchase order listed!');
          </script>";
        }else {
          echo "<script> alert('Failed!');
              </script>";
        }
    }else{
      echo "<script> alert('$message');
            </script>";
    }
  }/*End of main Submit conditional*/
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

                  <div class="panel-body"><br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                     <div class="form-group">
                        <p class="form-control-static">
                            <label>Raw Material:</label></br>
                              <select class="form-control" name="rawmat">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM RawMaterial');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['rawMaterialID']}\">{$row['name']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><br>
                             <label>Supplier:</label></br>
                               <select class="form-control" name="supplier">
                               <?php
                                 $result = mysqli_query($conn, 'SELECT * FROM Supplier');

                                 while($row = mysqli_fetch_array($result)){
                                   echo "<label><option value=\"{$row['supplierID']}\">{$row['name']}</option></label>
                                   <br>";
                                 }
                                ?>
                              </select><br>
                            <label>Quantity:</label></br>
                              <input type="number" name="quantity" class="form-control" required>
                            </br>
                            <label>Date ordered:</label></br>
                              <input type="date" name="orderdate" class="form-control" required>
                            </br>
                        </p>
                    <input type="submit" name="submit" value="Add RawMaterial" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
