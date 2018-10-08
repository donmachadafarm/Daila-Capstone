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


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header">
                  <!-- PAGE HEADER HERE -->
              </h1>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <!-- BODY HEADER -->
                  </div>
                  <div class="panel-body">
                    <h2>Homepage</h2>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
