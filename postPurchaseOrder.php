<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }

  $supid = $_GET['supplier'];

  function fill_unit_select_box($conn){
    $output = '';
    $supid = $_GET['supplier'];
    $query = "SELECT * FROM RawMaterial WHERE supplierID = $supid";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["rawMaterialID"].'">'.$row["name"].'</option>';
    }

    return $output;
  }
?>

<?php
  // Query

  // get supplier data from GET
  $sql = mysqli_query($conn,"SELECT * FROM Supplier WHERE supplierID = $supid");

  // run query for supplier data
  $resh = mysqli_fetch_array($sql);

  // Get date today
  $today = date("Y-m-d");
  if (isset($_POST['submit'])){
      // insert purchase order
      mysqli_query($conn,"INSERT into PurchaseOrder (supplierID,totalPrice,orderDate,status) values ('{$supid}','0','{$today}','Pending')");

      // select recently added PO
      $query="SELECT * FROM PurchaseOrder ORDER BY purchaseOrderID DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

        // get PO ID
        $poid = $row['purchaseOrderID'];

      // get total count of all added rawmats
      $count = count($_POST['rawmat']);

      // variables storing
      $rawmatid=$_POST['rawmat'];
      $qty=$_POST['quantity'];
      $total = 0;

      if($count > 0){
        // loop thru all rawmats added
        for($i=0;$i<$count;$i++){
          // select the rawmat with corresponding id
          $sql = mysqli_query($conn,"SELECT * FROM RawMaterial WHERE rawMaterialID = $rawmatid[$i]");
          $row = mysqli_fetch_array($sql);
          // multiply quantity to the price per unit for sub total per raw material
          $subtotal = $qty[$i] * $row['pricePerUnit'];
          $query="INSERT into POItem (purchaseOrderID,rawMaterialID,quantity,subTotal) values ('{$poid}','{$rawmatid[$i]}','{$qty[$i]}','{$subtotal}')";
              $sql = mysqli_query($conn,$query);
              $total+=$subtotal;
        }
        // finally update the total price for all raw materials
        $query = "UPDATE PurchaseOrder SET totalPrice = $total WHERE purchaseOrderID = $poid";

        mysqli_query($conn,$query);

        echo "<script>
          alert('Purchase Order Posted!');
            window.location.replace('viewPurchaseOrders.php');
              </script>";
      }else {
        echo "<script>
          alert('Purchase Order failed to post');
        </script>";
      }




  }
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><br><br>
                 Items for Purchase Order using Supplier: <small><?php echo $resh['name']; ?></small>
              </h2>
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
                         <th>Raw Material</th>
                         <th>Quantity</th>
                        </tr>
                        <tr>
                          <td><select name="rawmat[]" class="form-control item_unit" required><option value="" disabled>Select Raw Material</option><?php echo fill_unit_select_box($conn); ?></select></td>
                          <td><input type="number" name="quantity[]" class="form-control item_name" required /></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table>
                       <div align="center">
                        <input type="submit" name="submit" class="btn btn-success" value="Post Purchase Order!" />
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
      html += '<td><select name="rawmat[]" class="form-control item_unit"><option value="">Select Raw Material</option><?php echo fill_unit_select_box($conn); ?></select></td>';
      html += '<td><input type="number" name="quantity[]" class="form-control item_name" required /></td>';
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
