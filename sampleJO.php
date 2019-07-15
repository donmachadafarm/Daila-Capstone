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
    $query = "SELECT * FROM Product ORDER BY name ASC";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["productID"].'">'.$row["name"].'</option>';
    }

    return $output;
  }

  function fill_unit_select_box1($conn){
    $output = '';
    $query = "SELECT * FROM Customer WHERE customerID != '1' ORDER BY company ASC";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["customerID"].'">'.$row["company"].'</option>';
    }

    return $output;
  }

  $date = ("Y-m-d");
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

            <form method="post" action="samplejoprint.php" id="insert_form">

              <div class="form-row">
                <div class="form-group col-sm-6">
                  <label for="customer"><b>Customer:</b></label>
                  <select id="customer" class="form-control" name="customer">
                    <option value="" disabled>Select Customer</option><?php echo fill_unit_select_box1($conn); ?>
                  </select>

                  <small class="form-text text-muted">Not in the list of Customers? <a href="addCustomerToOrder.php?prod=''">Click here</a></small>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="table-repsonsive">
                       <table class="table table-borderless" id="item_table">
                        <tr>
                         <th>Product</th><br>
                         <th>Quantity</th>
                        </tr>
                        <tr>
                          <td><select name="product[]" class="form-control item_unit" required><option value="" disabled>Select Product</option><?php echo fill_unit_select_box($conn); ?></select></td>
                          <td><input type="number" name="quantity[]" class="form-control item_name" required /></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table><small class="form-text text-muted">Adding a new product? <a href="addProduct.php">Click here</a></small>
                    </div>
                  </div>
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

                                     </p>
                                   </div>
                                   <div class="modal-footer">
                                       <input type="submit" id="submit" name="submit" class="btn btn-success" value="Continue" />
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
