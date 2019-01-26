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
      $priceperunit=$_POST['priceperunit'];
      $unitofmeasurement=$_POST['unitofmeasurement'];
      $ing=$_POST['ingr'];

    if(!isset($message)){
      $query="INSERT INTO RawMaterial (name,supplierID,pricePerUnit,unitOfMeasurement)
                VALUES ('{$name}','{$supplier}','{$priceperunit}','{$unitofmeasurement}')";


        if (mysqli_query($conn,$query)) {
          //get the latest inserted raw material
          $query1="SELECT * FROM RawMaterial ORDER BY rawMaterialID DESC LIMIT 1";

            $sql = mysqli_query($conn,$query1);

            $row = mysqli_fetch_array($sql);

            $id = $row['rawMaterialID'];

          //insert the pairing for rawmat and supplier
          $query2="INSERT INTO Supply(rawMaterialID,supplierID)
                    VALUES('{$id}','{$supplier}')";

            $sql = mysqli_query($conn,$query2);

          //insert the pairing for rawmat and ingredient
          $query3="INSERT INTO RMIngredient(rawMaterialID,ingredientID)
                    VALUES('{$id}','{$ing}')";

            $sql = mysqli_query($conn,$query3);

          echo "<script>
            alert('Raw Material: $name is added');
          </script>";
        }else {
          echo "<script> alert('Failed!');
              </script>";
        }
    }
  }/*End of main Submit conditional*/
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Add Raw Material
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
                                  echo "<label><option value=\"{$row['supplierID']}\">{$row['company']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><small class="form-text text-muted">Not in the list of suppliers? <a href="addSupplier.php">Click here</a></small><br>
                            <label>Price per Unit:</label></br>
                              <input type="number" name="priceperunit" class="form-control" required>
                            </br>
                            <label>Unit of Measurement:</label></br>
                              <select name="unitofmeasurement" class="form-control item_name" required><option value="Liter">Liter</option><option value="Kilogram">Kilogram</option></select>
                            </br>
                             <label>Corresponding Ingredient:</label></br>
                              <select class="form-control" name="ingr">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM Ingredient');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['ingredientID']}\">{$row['name']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><small class="form-text text-muted">Not in the list of Ingredients? <a href="addIngredient.php">Click here</a></small><br>
                        </p>
                    <input type="submit" name="submit" value="Add Raw Material" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
