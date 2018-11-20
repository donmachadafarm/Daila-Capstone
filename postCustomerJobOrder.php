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

  function fill_unit_select_box($conn){
    $output = '';
    $query = "SELECT * FROM Product";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["productID"].'">'.$row["name"].'</option>';
    }

    return $output;
  }

  // Main submit conditional
  if (isset($_POST['submit'])){
      $custid = $_GET['customer'];
      // set variables from post
      $product=$_POST['product'];
      $quantity=$_POST['quantity'];
      $duedate=$_POST['deadline'];
      $remarks = $_POST['remarks'];
      $type = "Made to Order";
      $status = "Pending for approval";
      $datetoday = date("Y-m-d");
      $user = $_SESSION['userid'];
      $total = 0;

      // main table insert
      mysqli_query($conn,$query="INSERT INTO JobOrder (customerID,orderDate,dueDate,totalPrice,type,status,createdBy)
                  VALUES ('{$custid}','{$datetoday}','{$duedate}',0,'$type','$status','$user')");

      $query = "SELECT * FROM JobOrder ORDER BY orderID DESC LIMIT 1";

            $sql = mysqli_query($conn,$query);

            $row = mysqli_fetch_array($sql);

      $joid = $row['orderID'];

      $count = count(array_unique($_POST['product']));

      $result = array();
      // combines the duplicates of rawmat id and adds the qty
      foreach($product as $index => $value) {
          if(!isset($result[$value])) {
              $result[$value] = 0;
          }
          $result[$value] += $quantity[$index];
      }

      if($count>0){

        for($i = 0; $i<$count; $i++){
          $arkey = array_keys($result);

          $sql = mysqli_query($conn,"SELECT * FROM Product WHERE productID = $arkey[$i]");
          $row = mysqli_fetch_array($sql);

          $subtotal = $result[$arkey[$i]] * $row['productPrice'];

          $query = "INSERT INTO Receipt(orderID,productID,quantity,subTotal) VALUES('{$joid}','{$product[$i]}','{$result[$arkey[$i]]}','{$subtotal}')";

            $sql = mysqli_query($conn,$query);

            $total += $subtotal;
        }

        $query = "UPDATE JobOrder SET totalPrice = $total WHERE orderID = $joid";

        mysqli_query($conn,$query);

        echo "<script>
          alert('Job Order Posted!');
           window.location.replace('viewJobOrders.php');
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
                  Job Order Form
              </h1>

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
                       <table class="table table-borderless" id="item_table">
                        <tr>
                         <th>Product</th>
                         <th>Quantity</th>
                         <th>Remarks</th>
                        </tr>
                        <tr>
                          <td><select name="product[]" class="form-control item_unit" required><option value="" disabled>Select Product</option><?php echo fill_unit_select_box($conn); ?></select></td>
                          <td><input type="number" name="quantity[]" class="form-control item_name" required /></td>
                          <td><textarea class="form-control" rows="1" name="remarks"></textarea></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table>
                     </div>

                  </div>
                </div>
               </div>
               <hr class="style1">
               <div class="col-lg-4">
                     <div class="col">
                       <label><strong>Deadline to DAILA:</strong></label></br>
                         <input type="date" name="deadline" id="txtDate" class="form-control" required>
                       </br>
                     </div>
                     <div class="col">
                       <label><strong>Received by user:</strong></label>
                         <input type="text" readonly class="form-control-plaintext" name="userid" value="<?php echo $_SESSION['username']; ?>">
                       </br>
                     </div>
               </div>
               <div class="col-lg-12">
                 <div align="center">
                  <a href="#confirm" data-target="#confirm" data-toggle="modal"><button type="button" class="btn btn-success">Submit</button></a>
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
                                       <h6>Confirm Job Order?</h6>
                                       <br>
                                       <h6>1% will be added to total produced</h6>

                                     </p>
                                   </div>
                                   <div class="modal-footer">
                                       <input type="submit" id="submit" name="submit" class="btn btn-success" value="Post Job Order!" />
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

<script type="text/javascript">
  $(document).ready(function(){

   $(document).on('click', '.add', function(){
      var html = '';
      html += '<tr>';
      html += '<td><select name="product[]" class="form-control item_unit"><option value="">Select Product</option><?php echo fill_unit_select_box($conn); ?></select></td>';
      html += '<td><input type="number" name="quantity[]" class="form-control item_name" required /></td>';
      html += '<td><textarea class="form-control" rows="1" name="remarks"></textarea></td>';
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
