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
  $prodid = $_GET['ids'];
  $prodname = $_GET['name'];

  // main submit conditional
  if (isset($_POST['submit'])){
      // set variables from post
      $prodid = $_POST['prodid'];
      $quantity = $_POST['quantity'];
      $remarks = $_POST['remarks'];
      $duedate = $_POST['deadline'];
      $today = date("Y-m-d");
      $type = "Made to Stock";
      $status = "Pending for approval";
      $user = $_SESSION['userid'];

      $sql = mysqli_query($conn,"SELECT * FROM Product WHERE productID = $prodid");

      $row = mysqli_fetch_array($sql);

      // get total price
      $prodprice = $row['productPrice'] * $quantity;

      // query for insert single job order
      $query = "INSERT INTO JobOrder(customerID,orderDate,dueDate,totalPrice,remarks,type,status,createdBy)
                  VALUES ('1','$today','$duedate','$prodprice','$remarks','$type','$status','$user')";

      // conditional if successfully added job order
      if(mysqli_query($conn,$query)){
        $sql = mysqli_query($conn,"SELECT * FROM JobOrder ORDER BY orderID DESC LIMIT 1");

        $row = mysqli_fetch_array($sql);

        $joid = $row['orderID'];

        if(mysqli_query($conn,"INSERT INTO Receipt(orderID,productID,quantity,subtotal) VALUES('$joid','$prodid','$quantity','$prodprice')")){
          echo "<script>
            alert('Job order listed!');
             window.location.replace('viewJobOrders.php');
          </script>";
          }
        }

    }
?>

<!-- put all the contents here  -->

<br><br>
<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><br><br><a href="viewInventory.php" class="btn btn-primary btn-sm float-right">go back</a>
                  Job Order Form for <?php echo $prodname; ?>
              </h2>
          </div>
      </div>
      <hr class="style1">
      <div class="row">
          <div class="col-lg-8">
              <div class="panel panel-default">

                  <div class="panel-body"><br>
                    <form method="post">
                     <div class="form-group">
                        <p class="form-control-static">
                          <input type="hidden" name="prodid" value="<?php echo $prodid; ?>">
                          <label>Quantity:</label></br>
                            <input type="number" name="quantity" class="form-control" required>
                          </br>
                          <label>Due Date:</label></br>
                            <input type="date" id="txtDate" name="deadline" class="form-control" required>
                          </br>
                          <label>Remarks:</label>
                            <textarea class="form-control" rows="3" name="remarks"></textarea>
                          </br>
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
