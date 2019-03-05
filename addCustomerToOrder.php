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
      $fname=$_POST['firstname'];
      $lname=$_POST['lastname'];
      $email=$_POST['email'];
      $address=$_POST['address'];
      $contact=$_POST['contact'];
      $position=$_POST['position'];

      $query="INSERT INTO Customer (company,firstName,lastName,email,address,contactNum,position)
              VALUES ('{$company}','{$fname}','{$lname}','{$email}','{$address}','{$contact}','{$position}')";


      if (mysqli_query($conn,$query)) {

        $q = "SELECT * FROM Customer ORDER BY customerID DESC LIMIT 1";

        $sql = mysqli_query($conn,$q);

        $row = mysqli_fetch_array($sql);
        $c = $row['customerID'];
        echo "<script>
          window.location.replace('sampleJO.php');
        </script>";
      }else {
        echo "<script> alert('Failed to Add account!');
            </script>";
      }

  }

  /*End of main Submit conditional*/
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Add New Customer
              </h1>
          </div>
      </div>
      <hr class="style1">
      <div class="row">
          <div class="col-lg-8">
              <div class="panel panel-default">

                  <div class="panel-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="company">Company</label>
                          <input type="text" class="form-control" id="company" name="company">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="position">Position</label>
                          <input type="text" class="form-control" id="position" name="position">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="firstname">First Name</label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="lastname">Last Name</label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" >
                      </div>
                      <div class="form-group">
                        <label for="inputAddress">Address</label>
                        <textarea name="address" rows="3" class="form-control" ></textarea>
                      </div>
                      <div class="form-group">
                          <label for="contact">Contact Number</label>
                          <input type="text" class="form-control" id="contact" name="contact" >
                      </div>
                      <br>
                      <input type="submit" name="submit" value="Add Customer" class="btn btn-success"/> <br>

                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
