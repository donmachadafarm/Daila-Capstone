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
                    <form action="addrecipe.php" method="get">
                     <div class="form-group">
                        <p class="form-control-static">
                            <label>Name:</label></br>
                              <input type="text" name="name" class="form-control" required>
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
                             <label>Unit of Measurement:</label></br>
                               <select name="uom" class="form-control item_name" required>
                                 <option value="Pieces">Pieces</option>
                                 <option value="Liter">Liter</option>
                                 <option value="Gallon">Gallon</option>
                                 <option value="Kilogram">Kilogram</option>
                                 <option value="Bottles">Bottles</option>
                                 <option value="Pack">Pack</option>
                               </select>
                             </br>
                             <div class="form-check form-check-inline">
                               <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="1" name="custom">
                               <label class="form-check-label" for="inlineCheckbox1">Custom Product?</label>
                             </div>


                        </p>
                    <input type="submit" name="submit" value="Next" class="btn btn-success"/></div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
