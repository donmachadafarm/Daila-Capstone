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

  if (isset($_POST['continue'])) {
    $name=$_POST['name'];
    $email=$_POST['email'];
    $address=$_POST['address'];
    $contact=$_POST['number'];

    if(!isset($message)){
      $query="INSERT INTO Customer (name,email,address,contactNum) VALUES ('{$name}','{$email}','{$address}','{$contact}')";
        if (mysqli_query($conn,$query)) {

          $q = "SELECT * FROM Customer ORDER BY customerID DESC LIMIT 1";

          $sql = mysqli_query($conn,$q);

          $row = mysqli_fetch_array($sql);
          $c = $row['customerID'];
          echo "<script>
            window.location.replace('postCustomerJobOrder.php?customer=$c');
          </script>";
        }else {
          echo "<script> alert('Failed to Add account!');
              </script>";
        }
    }else{
      echo "<script> alert('$message');
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
                  Add Customer
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
                            <label>Email:</label></br>
                              <input type="email" name="email" class="form-control" required>
                            </br>
                            <label>Address:</label></br>
                              <input type="text" name="address" class="form-control" required>
                            </br>
                            <label>Contact Number:</label></br>
                              <input type="number" name="number" class="form-control" required>
                            </br>
                        </p>
                    <input type="submit" name="continue" value="Add and order" class="btn btn-success"/>
                    </div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
