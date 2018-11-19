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
      $address=$_POST['address'];
      $duration=$_POST['duration'];

    if(!isset($message)){
      $query="insert into Supplier (name,address,duration) values ('{$name}','{$address}','{$duration}')";
        if (mysqli_query($conn,$query)) {

          echo "<script>
            alert('Account of $name is created');
          </script>";
        }else {
          echo "<script> alert('Failed to Add account!');
              </script>";
        }
    }else{
      echo "<script> alert('{$message}');
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
                            <label>Address:</label>
                              <textarea class="form-control" rows="3" name="address"></textarea>
                            </br>
                            <label>Delivery Days:</label></br>
                              <input type="number" name="duration" class="form-control" required>
                            </br>
                        </p>
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
