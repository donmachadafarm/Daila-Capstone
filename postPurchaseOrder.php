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

  // main submit conditional start here for debugging submit purchase order
  if (isset($_POST['submit'])){
      // get deadline
      $deadline = $_POST['deadline'];
      $user = $_SESSION['userid'];

      // insert purchase order
      mysqli_query($conn,"INSERT into PurchaseOrder (supplierID,totalPrice,orderDate,status,deadline,createdBy) values ('{$supid}','0','{$today}','Pending','{$deadline}'),'{$user}'");

      // select recently added PO
      $query="SELECT * FROM PurchaseOrder ORDER BY purchaseOrderID DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

        // get PO ID
        $poid = $row['purchaseOrderID'];

      // get total count of all added rawmats
      $count = count(array_unique($_POST['rawmat']));


      // variables storing post variable(array) -> regular variable(array)
      $rawmatid=$_POST['rawmat'];
      $qty=$_POST['quantity'];
      $uom=$_POST['uom'];
      $total = 0;

      $result = array();
      // combines the duplicates of rawmat id and adds the qty
      foreach($rawmatid as $index => $value) {
          if(!isset($result[$value])) {
              $result[$value] = 0;
          }
          $result[$value] += $qty[$index];
      }


      if($count > 0){
        // loop thru all rawmats added
        for($i=0;$i<$count;$i++){
          // store the keys of the resulting array
          $arkey = array_keys($result);
          // select the rawmat with corresponding id
          $sql = mysqli_query($conn,"SELECT * FROM RawMaterial WHERE rawMaterialID = $arkey[$i]");
          $row = mysqli_fetch_array($sql);

          // multiply quantity to the price per unit for sub total per raw material
          $subtotal = $result[$arkey[$i]] * $row['pricePerUnit'];
          $uom = $row['unitOfMeasurement'];

          $query="INSERT into POItem (purchaseOrderID,rawMaterialID,quantity,subTotal,unitOfMeasurement,status)
                     values ('{$poid}','{$arkey[$i]}','{$result[$arkey[$i]]}','{$subtotal}','{$uom}','Not Delivered')";
              $sql = mysqli_query($conn,$query);
              $total+=$subtotal;
        }
        // // finally update the total price for all raw materials
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
                 Items list for Purchase Order
              </h2><h4>Supplier: <small><?php echo $resh['name']; ?></small> <a href="makePurchaseOrder.php" class="btn btn-primary btn-sm float-right">go back</a>
</h4>
              <hr class="style1">
          </div>
      </div>
      <div class="row">
        <div class="col-lg-12">


                    <form method="post" id="insert_form">
                      <div class="col-lg-12">
                        <div class="panel panel-default">
                          <div class="panel-body">

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
                             </div>

                          </div>
                        </div>
                       </div>
                       <hr class="style1">
                       <div class="col-lg-4">
                         <div class="container">
                           <label><strong>Deadline to supplier:</strong></label></br>
                             <input type="date" id="txtDate" name="deadline" class="form-control" required>
                           </br>
                         </div>
                       </div>
                       <div class="col-lg-12">
                         <div align="center">
                          <a href="#confirm" data-target="#confirm" data-toggle="modal"><button type="button" class="btn btn-success btn-sm">Submit</button></a>
                         </div>
                       </div>

                       <!-- // modal -->
                       <div id="confirm" class="modal fade" role="dialog">
                           <div class="modal-dialog">
                                   <div class="modal-content">

                                       <div class="modal-header">
                                           <h4>Notice</h4>
                                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                                       </div>

                                       <div class="modal-body">
                                           <div class="text-center">
                                             <p>
                                               <h6>Confirm Purchase Order?</h6>
                                               <br>

                                             </p>
                                           </div>
                                           <div class="modal-footer">
                                               <input type="submit" id="submit" name="submit" class="btn btn-success" value="Post Purchase Order!" />
                                               <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                           </div>
                                       </div>
                               </div>
                           </div>
                       </div>

                     </form>

        </div>
      </div>
  </div>
</div>

<!-- <script type="text/javascript">
  $('#submit').click(function(){
    $('#insert_form').submit();
  });
</script> -->

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
