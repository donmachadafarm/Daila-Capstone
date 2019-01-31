<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }
?>

<!-- put all the contents here  -->

<?php
  $id = $_GET['id'];

  $query = "SELECT * FROM Customer WHERE customerID = $id";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $company = $row['company'];
    $fname = $row['firstName'];
    $lname = $row['lastName'];
    $position = $row['position'];
    $email = $row['email'];
    $address = $row['address'];
    $contact = $row['contactNum'];

 ?>
<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   <?php echo $company; ?>
              </h1>Client Representative Profile<a href="viewCustomers.php" class="btn btn-primary btn-sm float-right">go back</a>
          </div>
      </div>
<br><br><br>

  <div class="card">
    <div class="card-header">
      <h5 class="text-center"><strong>Position: </strong><?php echo $position; ?></h5>
    </div>
    <div class="card-body">
      <form>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="name"><strong>First Name:</strong></label>
            <input type="text" class="form-control" readonly placeholder="<?php echo $fname; ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="name"><strong>Last Name:</strong></label>
            <input type="text" class="form-control" readonly placeholder="<?php echo $lname; ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="name"><strong>Email:</strong></label>
            <input type="text" class="form-control" readonly placeholder="<?php echo $email; ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="name"><strong>Contact Number:</strong></label>
            <input type="text" class="form-control" readonly placeholder="<?php echo $contact; ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="inputAddress"><strong>Address</strong></label>
          <textarea name="address" rows="3" class="form-control" placeholder="<?php echo $address; ?>" readonly></textarea>
        </div>
      </form>
    </div>
  </div>



      </div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
