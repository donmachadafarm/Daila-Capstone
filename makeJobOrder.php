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
      // set variables from post
      $rawmat=$_POST['rawmat'];
      $supplier=$_POST['supplier'];
      $quantity=$_POST['quantity'];
      $orderdate=$_POST['orderdate'];

      // main table insert
    if(!isset($message)){
      $query="INSERT into PurchaseOrder (rawMaterialID,supplierID,quantity,orderDate,status) values ('{$rawmat}','{$supplier}','{$quantity}','{$orderdate}','Pending')";


          echo "<script>
            alert('Purchase order listed!');
          </script>";
        }else {
          echo "<script> alert('Failed!');
              </script>";
        }
    }
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Job Order Form
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
                          <label>Product:</label></br>
                            <select class="form-control" name="product">
                            <?php
                              $result = mysqli_query($conn, 'SELECT * FROM Product');

                              while($row = mysqli_fetch_array($result)){
                                echo "<label><option value=\"{$row['productID']}\">{$row['name']}</option></label>
                                <br>";
                              }
                             ?>
                           </select><br>
                        </p>
                    <input type="submit" name="submit" value="Add Job Order" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
