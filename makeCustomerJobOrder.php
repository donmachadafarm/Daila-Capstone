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
                  Job Order - Made to Order
              </h1>
          </div>
      </div>
      <hr class="style1">
      <div class="row">
          <div class="col-lg-4">
              <div class="panel panel-default">

                  <div class="panel-body">
                    <form action="postCustomerJobOrder.php" method="get">
                     <div class="form-group">

                            <label><b>Select Customer:</b></label></br>
                              <select class="form-control" name="customer">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM Customer WHERE customerID <> "1"');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['customerID']}\">{$row['name']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><small class="form-text text-muted">Not in the list of Customers? <a href="addCustomer.php">Click here</a></small><br>
                             <small class="form-text text-muted">Adding a custom product? <a href="addProduct.php">Click here</a></small><br>

                    <input type="submit" name="submit" value="Continue" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
