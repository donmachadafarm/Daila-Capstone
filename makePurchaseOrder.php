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
                    <form action="postPurchaseOrder.php" method="GET">
                     <div class="form-group">
                        <p class="form-control-static">
                              <label>Supplier:</label></br>
                               <select class="form-control" name="supplier" id="supplier-list">
                               <?php
                                 $result = mysqli_query($conn, 'SELECT * FROM Supplier');

                                 while($row = mysqli_fetch_array($result)){
                                   echo "<label><option value=\"{$row['supplierID']}\">{$row['name']}</option></label>
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
      </div>
  </div>
</div>



<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
