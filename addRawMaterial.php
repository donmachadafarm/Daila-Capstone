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

      $name=$_POST['name'];
      $supplier=$_POST['supplier'];
      $quantity=0;
      $capperunit=$_POST['capacityperunit'];
      $priceperunit=$_POST['priceperunit'];
      $unitofmeasurement=$_POST['unitofmeasurement'];
      $rawmattype=$_POST['rawmattype'];

    if(!isset($message)){
      $query="INSERT into RawMaterial (name,supplierID,quantity,capacityPerUnit,pricePerUnit,unitOfMeasurement,rawMaterialTypeID)
                values ('{$name}','{$supplier}','{$quantity}','{$capperunit}','{$priceperunit}','{$unitofmeasurement}','{$rawmattype}')";

        if (mysqli_query($conn,$query)) {
          $query2="SELECT * FROM RawMaterial ORDER BY rawMaterialID DESC LIMIT 1";

            $sql = mysqli_query($conn,$query2);

            $row = mysqli_fetch_array($sql);

            $id = $row['rawMaterialID'];

          $query3="INSERT INTO Supply(rawMaterialID,supplierID)
                    VALUES('{$id}','{$supplier}')";

            $sql = mysqli_query($conn,$query3);

          echo "<script>
            alert('Raw Material: $name is added');
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
                  Add RawMaterial
              </h1>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-8">
              <div class="panel panel-default">

                  <div class="panel-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                     <div class="form-group">
                        <p class="form-control-static">
                            <label>Name:</label></br>
                              <input type="text" name="name" class="form-control" required>
                            </br>
                            <label>Supplier:</label></br>
                              <select class="form-control" name="supplier">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM Supplier');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['supplierID']}\">{$row['name']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><small class="form-text text-muted">Not in the list of suppliers? <a href="addSupplier.php">Click here</a></small><br>
                             <label>Capacity per unit:</label></br>
                               <input type="number" name="capacityperunit" class="form-control" required>
                             </br>
                            <label>Price per unit:</label></br>
                              <input type="number" name="priceperunit" class="form-control" required>
                            </br>
                            <label>Unit of Measurement:</label></br>
                              <input type="text" name="unitofmeasurement" class="form-control" required>
                            </br>
                            <label>RawMaterial Type:</label></br>
                              <select class="form-control" name="rawmattype">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM RawMaterialType');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['rawMaterialTypeID']}\">{$row['name']}</option></label>
                                  </br>";
                                }
                               ?>
                              </select>
                        </p>
                    <input type="submit" name="submit" value="Add RawMaterial" class="btn btn-success"/></div>
                    </form>
                    <small class="form-text text-muted">Add the corresponding ingredient? <a href="addIngredient.php">Click here</a></small>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
