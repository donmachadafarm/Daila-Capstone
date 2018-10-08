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

      $desc=$_POST['desc'];
      $name=$_POST['name'];
      $quantity=0;
      $producttype=$_POST['type'];
      $prodprice=$_POST['price'];


    if(!isset($message)){
      $query="insert into Product (description,name,quantity,productTypeID,productPrice) values ('{$desc}','{$name}','{$quantity}','{$producttype}','{$prodprice}')";
        if (mysqli_query($conn,$query)) {

          echo "<script>
            alert('Product $name is added');
          </script>";
        }else {
          echo "<script> alert('Failed!');
              </script>";
        }
    }else{
      echo "<script> alert('$message');
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
                  Add Product
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
                            <label>Description:</label></br>
                              <textarea class="form-control" rows="3" name="desc"></textarea>
                            </br>
                            <label>Product Type:</label></br>
                              <select class="form-control" name="type">
                              <?php
                                $result = mysqli_query($conn, 'SELECT * FROM ProductType');

                                while($row = mysqli_fetch_array($result)){
                                  echo "<label><option value=\"{$row['productTypeID']}\">{$row['name']}</option></label>
                                  <br>";
                                }
                               ?>
                             </select><br>
                             <label>Price:</label></br>
                               <input type="number" name="price" class="form-control" required>
                             </br>
                        </p>
                    <input type="submit" name="submit" value="Add Product" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
