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

      $ingid=$_POST['ingredient'];
      $prodid=$_POST['product'];
      $quantity=$_POST['quantity'];
      $uom=$_POST['uom'];

    if(!isset($message)){
      $query="insert into Recipe (ingredientID,productID,quantity,unitOfMeasurement) values ('{$ingid}','{$prodid}','{$quantity}','{$uom}')";
        if (mysqli_query($conn,$query)) {

          echo "<script>
            alert('Recipe for $prodid is added');
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
                  Add Recipe
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
                            <label>Recipe for Product:</label></br>
                              <select class="form-control" name="product">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM Product');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['productID']}\">{$row['name']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><br>
                             <label>Using ingredient:</label></br>
                               <select class="form-control" name="ingredient">
                               <?php
                                 $result = mysqli_query($conn, 'SELECT * FROM Ingredient');

                                 while($row = mysqli_fetch_array($result)){
                                   echo "<label><option value=\"{$row['ingredientID']}\">{$row['name']}</option></label>
                                   <br>";
                                 }
                                ?>
                              </select><br>
                             <label>Quantity:</label></br>
                               <input type="number" name="quantity" class="form-control" required>
                             </br>
                             <label>Unit of measurement:</label></br>
                               <input type="text" name="uom" class="form-control" required>
                             </br>
                        </p>
                    <input type="submit" name="submit" value="Add Recipe" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
