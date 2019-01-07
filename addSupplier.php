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

      $company=$_POST['company'];
      $fname=$_POST['fname'];
      $lname=$_POST['lname'];
      $landline=$_POST['landline'];
      $mobile=$_POST['mobile'];
      $address=$_POST['address'];
      $duration=$_POST['duration'];

      $query="INSERT INTO Supplier (name,contactNum,address,duration) values ('{$name}','{$contact}','{$address}','{$duration}')";

      $query = "INSERT INTO `Supplier` (`company`,`firstName`,`lastName`,`landline`, `mobileNumber`, `address`, `duration`)
                  VALUES('$company','$fname','$lname','$landline','$mobile', '$address',$duration)";

        if (mysqli_query($conn,$query)) {

          echo "<script>
            alert('Supplier $fname from $company is created');
          </script>";
        }else {
          echo "<script> alert('Failed to Add account!');
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
                  Add Supplier
              </h1>
          </div>
      </div>
      <hr class="style1">
      <div class="row">
          <div class="col-lg-8">
              <div class="panel panel-default">

                  <div class="panel-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                      <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" class="form-control" id="company" name="company">
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="firstname">First Name</label>
                          <input type="text" class="form-control" id="firstname" name="fname">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="lastname">Last Name</label>
                          <input type="text" class="form-control" id="lastname" name="lname">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputAddress">Address</label>
                        <textarea name="address" rows="3" class="form-control" ></textarea>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="landline">Landline</label>
                          <input type="text" class="form-control" id="landline" name="landline">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="mobile">Mobile Number</label>
                          <input type="text" class="form-control" id="mobile" name="mobile" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="duration">Delivery Time(days)</label>
                        <input type="number" class="form-control" id="duration" name="duration">
                      </div>
                    <input type="submit" name="submit" value="Add Supplier" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
