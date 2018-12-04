<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }

  function fill_unit_select_box($conn){
    $output = '';
    $query = "SELECT Ingredient.name AS iname,Ingredient.ingredientID AS iid FROM Ingredient";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["iid"].'">'.$row["iname"].'</option>';
    }

    return $output;
  }
?>

<?php
  // Query
      $name=$_GET['name'];
      $quantity=0;
      $producttype=$_GET['type'];
      $prodprice=$_GET['price'];
      $uom =$_GET['uom'];


  if (isset($_POST['submit'])){
      mysqli_query($conn,"INSERT into Product (name,quantity,productTypeID,productPrice,unitOfMeasurement) values ('{$name}','{$quantity}','{$producttype}','{$prodprice}','{$uom}')");

      $query="SELECT * FROM Product ORDER BY productID DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

        $proid = $row['productID'];

      $count = count(array_unique($_POST['ingredient']));

      $ingid=$_POST['ingredient'];
      $qty=$_POST['quantity'];
      $uom=$_POST['uom'];

      $result = array();
      // combines the duplicates of rawmat id and adds the qty
      foreach($ingid as $index => $value) {
          if(!isset($result[$value])) {
              $result[$value] = 0;
          }
          $result[$value] += $qty[$index];
      }
      // print_r($result);

      if($count > 0){
        for($i=0;$i<$count;$i++){
          $arkey = array_keys($result);

          $sql3 = mysqli_query($conn,"SELECT DISTINCT RawMaterial.unitOfMeasurement AS uom FROM RawMaterial
                                        INNER JOIN RMIngredient ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                        INNER JOIN Ingredient ON Ingredient.ingredientID = RMIngredient.ingredientID
                                        WHERE Ingredient.ingredientID = {$arkey[$i]}");
                $row = mysqli_fetch_row($sql3);
                $unitom = $row[0];

          $query="INSERT into Recipe (ingredientID,productID,quantity,unitOfMeasurement) values ('{$arkey[$i]}','{$proid}','{$result[$arkey[$i]]}','{$unitom}')";
              $sql = mysqli_query($conn,$query);
        }
      }

      echo "<script>
        alert('Recipes added for product $name!');
        window.location.replace('addProductProcess.php?id=".$proid."');
            </script>";


  }
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Add Recipe for product <?php echo $name; ?>
              </h1>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-default">

                  <div class="panel-body">
                    <form method="post" id="insert_form">
                      <div class="table-repsonsive">
                       <span id="error"></span>
                       <table class="table table-borderless" id="item_table">
                        <tr>
                         <th>Enter Ingredient</th>
                         <th>Enter Quantity</th>
                         <th>Select Unit of Measurement</th>
                        </tr>
                        <tr>
                          <td><select name="ingredient[]" class="form-control item_unit" required><option value="">Select Ingredient</option><?php echo fill_unit_select_box($conn); ?></select></td>
                          <td><input type="number" name="quantity[]" class="form-control item_name" step="0.01" required /></td>
                          <td><select name="uom[]" class="form-control item_name" required><option value="Liter">Liter</option><option value="Kilogram">Kilogram</option></select></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table>
                       <div align="center">
                        <input type="submit" name="submit" class="btn btn-success" value="Submit" />
                       </div>
                      </div>
                     </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

   $(document).on('click', '.add', function(){
      var html = '';
      html += '<tr>';
      html += '<td><select name="ingredient[]" class="form-control item_unit"><option value="">Select Ingredient</option><?php echo fill_unit_select_box($conn); ?></select></td>';
      html += '<td><input type="number" name="quantity[]" class="form-control item_name" step="0.01" required /></td>';
      html += '<td><select name="uom[]" class="form-control item_name" required><option value="Liter">Liter</option><option value="Kilogram">Kilogram</option></select></td>';
      html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">x</button></td></tr>';
      $('#item_table').append(html);
     });

     $(document).on('click', '.remove', function(){
      $(this).closest('tr').remove();
     });

  });
</script>





<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
