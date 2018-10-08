<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }
?>

<?php

  if (isset($_POST['submit'])){

      $name=$_POST['name'];
      // $rm=$_POST['rawmat'];

    if(!isset($message)){
      $query="INSERT into Ingredient (quantity,name) values ('0','{$name}')";
        if (mysqli_query($conn,$query)) {

          // $query1="SELECT * FROM RawMaterial WHERE rawMaterialID = $rm";
          //
          //   $sql = mysqli_query($conn,$query1);
          //
          //   $row=mysqli_fetch_array($sql);
          //
          //   $id1 = $row['rawMaterialID'];
          //
          // $query2="SELECT * FROM Ingredient ORDER BY ingredientID DESC LIMIT 1";
          //
          //   $sql = mysqli_query($conn,$query2);
          //
          //   $row=mysqli_fetch_array($sql);
          //
          //   $id2 = $row['ingredientID'];
          //
          // $query3="INSERT INTO RMIngredient (rawMaterialID,ingredientID) VALUES ('{$id1}','{$id2}')";
          //
          //   $sql = mysqli_query($conn,$query3);

              echo "<script>
                alert('Ingredient $name is added');
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
                  Add Ingredient
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
                              <!-- <label>Corresponding Raw Material:</label></br>
                               <select class="form-control" name="rawmat">
                               <?php
                                 // $result = mysqli_query($conn, 'SELECT * FROM RawMaterial');
                                 //
                                 // while($row = mysqli_fetch_array($result)){
                                 //   echo "<label><option value=\"{$row['rawMaterialID']}\">{$row['name']}</option></label>
                                 //   <br>";
                                 // }
                                ?>
                              </select><small class="form-text text-muted">Not in the list of Raw Materials? <a href="addRawMaterial.php">Click here</a></small><br> -->
                        </p>
                    <input type="submit" name="submit" value="Add Ingredient" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
